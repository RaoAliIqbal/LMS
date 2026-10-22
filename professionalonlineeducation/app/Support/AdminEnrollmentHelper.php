<?php

namespace App\Support;

use App\Models\Lesson;
use App\Models\LessonUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Admin enrollment without EducationService (avoids PHP 8 / autoload failures on live).
 */
class AdminEnrollmentHelper
{
    public static function enrollUserInCourse(int $userId, int $lessonId, array $options = []): array
    {
        try {
            if (! Schema::hasTable('lesson_users')) {
                return ['type' => 'error', 'msg' => 'Database table lesson_users is missing. '.AdminDbLoader::PATCH_HINT];
            }

            $course = Lesson::find($lessonId);
            if (! $course) {
                $row = DB::table('lessons')->where('id', $lessonId)->first();
                if (! $row) {
                    return ['type' => 'error', 'msg' => 'Course not found.'];
                }
            }

            if (! DB::table('users')->where('id', $userId)->exists()) {
                return ['type' => 'error', 'msg' => 'Student not found.'];
            }

            $year = (int) ($options['year'] ?? date('Y'));
            $price = array_key_exists('price', $options)
                ? $options['price']
                : ($course ? $course->price : 0);

            $existing = LessonUser::where('user_id', $userId)
                ->where('lesson_id', $lessonId)
                ->whereYear('created_at', $year)
                ->first();

            if ($existing) {
                self::initChaptersForUser($userId, $lessonId, $year);

                return [
                    'type' => 'success',
                    'msg' => 'Student is already enrolled in this course.',
                    'enrollment_id' => $existing->id,
                ];
            }

            $now = Carbon::now();
            $enrollment = LessonUser::create([
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'enrolled_at' => $now,
                'price' => $price,
                'status' => 1,
                'is_show' => 0,
            ]);

            self::initChaptersForUser($userId, $lessonId, $year);

            return [
                'type' => 'success',
                'msg' => 'Course added to student profile successfully.',
                'enrollment_id' => $enrollment->id,
            ];
        } catch (\Throwable $e) {
            \Log::error('AdminEnrollmentHelper::enrollUserInCourse failed', [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'error' => $e->getMessage(),
            ]);

            return [
                'type' => 'error',
                'msg' => 'Could not add course: '.$e->getMessage(),
            ];
        }
    }

    protected static function initChaptersForUser(int $userId, int $lessonId, int $year): void
    {
        if (! Schema::hasTable('chapters') || ! Schema::hasTable('lesson_chapter_users')) {
            return;
        }

        try {
            $chapters = DB::table('chapters')->where('lesson_id', $lessonId)->get();
            if ($chapters->isEmpty()) {
                return;
            }

            foreach ($chapters as $chapter) {
                $exists = DB::table('lesson_chapter_users')
                    ->where('user_id', $userId)
                    ->where('lesson_id', $lessonId)
                    ->where('chapter_id', $chapter->id)
                    ->whereYear('created_at', $year)
                    ->exists();

                if ($exists) {
                    continue;
                }

                // New enrollment year = fresh attempt; never inherit last year's progress.
                $now = Carbon::now();
                DB::table('lesson_chapter_users')->insert([
                    'user_id' => $userId,
                    'lesson_id' => $lessonId,
                    'chapter_id' => $chapter->id,
                    'time_spent' => 0,
                    'quiz_percentage' => null,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        } catch (\Throwable $e) {
            \Log::warning('AdminEnrollmentHelper: chapter init failed (enrollment still saved)', [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
