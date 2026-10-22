<?php

namespace App\Support;

use App\Models\PaymentLogs;
use App\Services\EducationService;
use App\Services\PaymentService;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class StudentEnrollmentLoader
{
    /**
     * Known enrollments from production backup when lesson_users rows are missing on live.
     *
     * @var array<string, array<int, array<string, mixed>>>
     */
    protected static $knownEnrollments = [
        'drcanham@professionalonlineeducation.com' => [
            ['lesson_id' => 38, 'enrolled_at' => '2023-02-07 00:55:56', 'price' => 399],
            ['lesson_id' => 16, 'enrolled_at' => '2023-02-07 00:56:10', 'price' => 300],
            ['lesson_id' => 22, 'enrolled_at' => '2023-02-07 00:56:18', 'price' => 199],
        ],
    ];

    public static function loadForUser(int $userId, ?string $email = null, $limit = false): Collection
    {
        if ($userId < 1) {
            return collect();
        }

        $courses = self::queryEnrollments($userId, $limit);

        if ($courses->isEmpty() && $email) {
            $resolvedId = self::resolveUserIdByEmail($email);
            if ($resolvedId > 0 && $resolvedId !== $userId) {
                Log::warning('StudentEnrollmentLoader: retried by email user id', [
                    'auth_user_id' => $userId,
                    'resolved_user_id' => $resolvedId,
                    'email' => $email,
                ]);
                $courses = self::queryEnrollments($resolvedId, $limit);
                $userId = $resolvedId;
            }
        }

        return $courses;
    }

    /**
     * Restore missing rows from payment logs and known backup snapshots, then reload.
     */
    public static function ensureEnrollments(User $user, $limit = false): Collection
    {
        $userId = (int) $user->id;
        $email = strtolower(trim((string) $user->email));

        self::restoreFromPaymentLogs($userId, $email);
        self::restoreFromKnownSnapshot($userId, $email);

        return self::loadForUser($userId, $email, $limit);
    }

    protected static function resolveUserIdByEmail(string $email): int
    {
        if (! Schema::hasTable('users') || $email === '') {
            return 0;
        }

        $id = DB::table('users')->where('email', $email)->value('id');

        return $id ? (int) $id : 0;
    }

    protected static function restoreFromPaymentLogs(int $userId, string $email): void
    {
        if (! Schema::hasTable('payment_logs')) {
            return;
        }

        $paymentService = new PaymentService();
        $educationService = new EducationService();

        $logs = PaymentLogs::query()
            ->where(function ($q) use ($userId, $email) {
                $q->where('user_id', $userId);
                if ($email !== '') {
                    $q->orWhere('email', $email);
                }
            })
            ->where('status', 1)
            ->orderBy('id')
            ->get();

        foreach ($logs as $log) {
            [$lessonIds] = $paymentService->parseLessonAndSeminarIds($log);
            if (! count($lessonIds)) {
                continue;
            }

            $year = (int) optional($log->created_at)->format('Y') ?: (int) date('Y');

            foreach ($lessonIds as $lessonId) {
                $exists = DB::table('lesson_users')
                    ->where('user_id', $userId)
                    ->where('lesson_id', (int) $lessonId)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $result = $educationService->grantCourseToUser($userId, (int) $lessonId, [
                    'price' => $log->amount,
                    'year' => $year,
                ]);

                Log::info('StudentEnrollmentLoader: restored from payment_log', [
                    'user_id' => $userId,
                    'lesson_id' => $lessonId,
                    'payment_log_id' => $log->id,
                    'result' => $result['type'] ?? 'unknown',
                ]);
            }
        }
    }

    protected static function restoreFromKnownSnapshot(int $userId, string $email): void
    {
        if ($email === '' || ! isset(self::$knownEnrollments[$email])) {
            return;
        }

        $educationService = new EducationService();

        foreach (self::$knownEnrollments[$email] as $row) {
            $lessonId = (int) ($row['lesson_id'] ?? 0);
            if ($lessonId < 1) {
                continue;
            }

            $exists = DB::table('lesson_users')
                ->where('user_id', $userId)
                ->where('lesson_id', $lessonId)
                ->exists();

            if ($exists) {
                continue;
            }

            $enrolledAt = $row['enrolled_at'] ?? now()->toDateTimeString();
            $year = (int) date('Y', strtotime($enrolledAt));

            $result = $educationService->grantCourseToUser($userId, $lessonId, [
                'price' => $row['price'] ?? null,
                'year' => $year,
            ]);

            if (($result['type'] ?? '') !== 'success') {
                self::insertEnrollmentRow($userId, $lessonId, $row);
            } elseif (Schema::hasColumn('lesson_users', 'enrolled_at')) {
                $enrollmentId = (int) ($result['enrollment_id'] ?? 0);
                if ($enrollmentId > 0) {
                    DB::table('lesson_users')->where('id', $enrollmentId)->update([
                        'enrolled_at' => $enrolledAt,
                        'created_at' => $enrolledAt,
                        'updated_at' => $enrolledAt,
                    ]);
                }
            }

            Log::info('StudentEnrollmentLoader: restored from known snapshot', [
                'user_id' => $userId,
                'email' => $email,
                'lesson_id' => $lessonId,
                'result' => $result['type'] ?? 'direct_insert',
            ]);
        }
    }

    protected static function insertEnrollmentRow(int $userId, int $lessonId, array $row): void
    {
        if (! Schema::hasTable('lesson_users')) {
            return;
        }

        $exists = DB::table('lesson_users')
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->exists();

        if ($exists) {
            return;
        }

        $enrolledAt = $row['enrolled_at'] ?? now()->toDateTimeString();
        $insert = [
            'lesson_id' => $lessonId,
            'user_id' => $userId,
            'created_at' => $enrolledAt,
            'updated_at' => $enrolledAt,
        ];

        if (Schema::hasColumn('lesson_users', 'enrolled_at')) {
            $insert['enrolled_at'] = $enrolledAt;
        }
        if (Schema::hasColumn('lesson_users', 'completed_at')) {
            $insert['completed_at'] = null;
        }
        if (Schema::hasColumn('lesson_users', 'price')) {
            $insert['price'] = $row['price'] ?? null;
        }
        if (Schema::hasColumn('lesson_users', 'status')) {
            $insert['status'] = 1;
        }
        if (Schema::hasColumn('lesson_users', 'is_show')) {
            $insert['is_show'] = 0;
        }

        DB::table('lesson_users')->insert($insert);

        Log::info('StudentEnrollmentLoader: direct enrollment insert', [
            'user_id' => $userId,
            'lesson_id' => $lessonId,
        ]);
    }

    protected static function queryEnrollments(int $userId, $limit = false): Collection
    {
        try {
            if (! Schema::hasTable('lesson_users')) {
                Log::warning('StudentEnrollmentLoader: lesson_users table missing', ['user_id' => $userId]);

                return collect();
            }

            $luSelect = self::lessonUserSelectColumns('lu');
            if (empty($luSelect)) {
                $luSelect = ['lu.id', 'lu.lesson_id', 'lu.user_id'];
            }

            $query = DB::table('lesson_users as lu')->where('lu.user_id', $userId);

            if (Schema::hasTable('lessons')) {
                $query->leftJoin('lessons as l', 'l.id', '=', 'lu.lesson_id');
                $luSelect[] = 'l.title';
                if (Schema::hasColumn('lessons', 'hours')) {
                    $luSelect[] = 'l.hours';
                }
            }

            $rows = $query
                ->select($luSelect)
                ->orderByDesc('lu.id')
                ->get()
                ->unique('lesson_id')
                ->values();

            if ($limit) {
                $rows = $rows->take((int) $limit);
            }

            $lessonIds = $rows->pluck('lesson_id')->filter()->unique()->values();
            $categoryMap = collect();
            if ($lessonIds->isNotEmpty() && Schema::hasTable('course_categories')) {
                $categoryMap = DB::table('course_categories')
                    ->whereIn('course_id', $lessonIds)
                    ->get()
                    ->groupBy('course_id');
            }

            $quizMap = collect();
            if ($lessonIds->isNotEmpty() && Schema::hasTable('user_quizzes')) {
                $quizMap = DB::table('user_quizzes')
                    ->where('user_id', $userId)
                    ->whereIn('lesson_id', $lessonIds)
                    ->select('lesson_id', 'quiz_id', 'user_percentage', 'created_at')
                    ->orderBy('created_at')
                    ->get()
                    ->groupBy(function ($quiz) {
                        return $quiz->lesson_id.'-'.date('Y', strtotime($quiz->created_at));
                    });
            }

            return $rows->map(function ($row) use ($categoryMap, $quizMap) {
                $year = date('Y', strtotime($row->created_at ?? $row->enrolled_at ?? 'now'));
                $quizAttempts = $quizMap->get($row->lesson_id.'-'.$year, collect())
                    ->groupBy('quiz_id')
                    ->map->last()
                    ->values();

                return self::mapEnrollmentRow($row, $categoryMap, $quizAttempts);
            });
        } catch (\Throwable $e) {
            Log::error('StudentEnrollmentLoader: query failed', [
                'user_id' => $userId,
                'message' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    protected static function lessonUserSelectColumns(string $alias): array
    {
        $columns = [
            'id', 'lesson_id', 'user_id', 'enrolled_at', 'completed_at',
            'price', 'status', 'is_show', 'created_at', 'updated_at',
        ];
        $select = [];

        foreach ($columns as $column) {
            if (Schema::hasColumn('lesson_users', $column)) {
                $select[] = "{$alias}.{$column}";
            }
        }

        return $select;
    }

    protected static function mapEnrollmentRow($row, Collection $categoryMap, Collection $quizAttempts): object
    {
        $lesson = (object) [
            'id' => (int) $row->lesson_id,
            'title' => ($row->title ?? null) ?: ('Course #'.$row->lesson_id),
            'hours' => $row->hours ?? null,
            'categories' => ($categoryMap[$row->lesson_id] ?? collect())->values(),
        ];

        return (object) [
            'id' => $row->id,
            'lesson_id' => $row->lesson_id,
            'user_id' => $row->user_id,
            'enrolled_at' => $row->enrolled_at ?? $row->created_at ?? null,
            'completed_at' => $row->completed_at ?? null,
            'price' => $row->price ?? null,
            'status' => $row->status ?? 1,
            'is_show' => $row->is_show ?? 0,
            'created_at' => $row->created_at ?? null,
            'updated_at' => $row->updated_at ?? null,
            'quiz_scores' => $quizAttempts->pluck('user_percentage')->map(function ($score) {
                return (float) $score;
            })->values(),
            'average_quiz_score' => $quizAttempts->isNotEmpty()
                ? round($quizAttempts->avg(function ($quiz) { return (float) $quiz->user_percentage; }), 1)
                : null,
            'course' => $lesson,
            'courseWithTrashed' => $lesson,
        ];
    }
}
