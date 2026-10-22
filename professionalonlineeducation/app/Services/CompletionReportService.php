<?php
namespace App\Services;

use App\Models\LessonUser;
use App\Models\LessonChapterUser;
use App\Models\UserQuiz;
use App\Models\UserEvaluation;
use App\Http\Helpers\General\Helper;
use App\Notifications\CourseCompletionAdminNotification;
use App\Notifications\EvaluationNotification;

class CompletionReportService
{
    public function data(LessonUser $enrollment): array
    {
        if (!$enrollment->completed_at || !$enrollment->user || !$enrollment->course) {
            throw new \RuntimeException('A completed enrollment, student and course are required.');
        }
        // Use the selected attempt's time window, not the most recent enrollment in a year.
        $start = $enrollment->enrolled_at ?: $enrollment->getRawOriginal('created_at');
        $next = LessonUser::where('user_id', $enrollment->user_id)->where('lesson_id', $enrollment->lesson_id)
            ->where('created_at', '>', $enrollment->getRawOriginal('created_at'))->orderBy('created_at')->first();
        $end = $enrollment->completed_at;
        if ($next && $next->getRawOriginal('created_at') < $end) $end = $next->getRawOriginal('created_at');
        $scope = function ($query) use ($enrollment, $start, $end) {
            return $query->where('user_id', $enrollment->user_id)->where('lesson_id', $enrollment->lesson_id)
                ->whereBetween('created_at', [$start, $end]);
        };
        $chapters = $scope(LessonChapterUser::query())->with('chapter')->get();
        $quizzes = $scope(UserQuiz::query())->with('quiz')->get();
        $scores = $quizzes->filter(function ($quiz) { return is_numeric($quiz->user_percentage); });
        // Evaluations may be submitted after completion; stop at the next enrollment.
        $evaluationQuery = UserEvaluation::where('user_id', $enrollment->user_id)->where('lesson_id', $enrollment->lesson_id)->where('created_at', '>=', $start);
        if ($next) $evaluationQuery->where('created_at', '<', $next->getRawOriginal('created_at'));
        $evaluation = $evaluationQuery->orderBy('id', 'desc')->first();
        return ['user'=>$enrollment->user, 'lesson'=>$enrollment->course, 'userLesson'=>$enrollment,
            'userLessonChapters'=>$chapters, 'userQuizzes'=>$quizzes,
            'totalPercent'=>$scores->isEmpty() ? null : $scores->avg('user_percentage'), 'evaluation'=>$evaluation];
    }

    public function resend(LessonUser $enrollment): bool
    {
        $data = $this->data($enrollment);
        if (!Helper::sendNotificationSafely(Helper::adminUser(), new CourseCompletionAdminNotification($data), 'completion report resend')) {
            throw new \RuntimeException('Completion report could not be sent. Check the SMTP configuration before retrying.');
        }
        if (!$data['evaluation']) return false;
        $evaluationData = ['request'=>$data['evaluation'], 'user'=>$data['user'], 'lesson'=>$data['lesson'], 'userLesson'=>$enrollment];
        if (!Helper::sendNotificationSafely(Helper::adminUser(), new EvaluationNotification($evaluationData), 'evaluation report resend')) {
            throw new \RuntimeException('Completion report sent, but evaluation delivery failed. Retrying may duplicate the completion report.');
        }
        return true;
    }
}

