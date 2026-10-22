<?php

namespace App\Console\Commands;

use App\Http\Helpers\General\Helper;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\UserEvaluation;
use App\Notifications\EvaluationNotification;
use App\Services\EducationService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResendCompletionEmails extends Command
{
    protected $signature = 'emails:resend-completions
                            {--from= : Required first completion date (YYYY-MM-DD)}
                            {--to= : Optional end date (inclusive)}
                            {--user= : Limit to one user id}
                            {--dry-run : List what would be sent without sending}';

    protected $description = 'Re-send course-completion and evaluation emails for students who completed courses in a date range.';

    public function handle(): int
    {
        if (!$this->option('from')) {
            $this->error('Specify --from=YYYY-MM-DD; confirm the year before recovery.');
            return 1;
        }
        $from = Carbon::parse($this->option('from'))->startOfDay();
        $to = $this->option('to') ? Carbon::parse($this->option('to'))->endOfDay() : Carbon::now();
        $dry = (bool) $this->option('dry-run');

        $query = LessonUser::whereNotNull('completed_at')
            ->whereBetween('completed_at', [$from, $to])
            ->orderBy('completed_at');
        if ($this->option('user')) {
            $query->where('user_id', (int) $this->option('user'));
        }

        $rows = $query->get();
        $this->info("Found {$rows->count()} completed enrollment(s) between {$from->toDateString()} and {$to->toDateString()}.");

        $service = new EducationService();
        $sent = 0;
        $failed = 0;

        foreach ($rows as $lu) {
            $user = User::find($lu->user_id);
            $lesson = Lesson::withTrashed()->find($lu->lesson_id);
            if (! $user || ! $lesson) {
                continue;
            }
            $label = "{$user->email} | {$lesson->title} | completed {$lu->completed_at}";
            if ($dry) {
                $this->line("[dry-run] {$label}");
                continue;
            }

            try {
                $hasEvaluation = (new \App\Services\CompletionReportService())->resend($lu);
                if (!$hasEvaluation) $this->warn('No saved evaluation for enrollment '.$lu->id);
                $sent++;
                $this->line("sent: {$label}");
            } catch (\Throwable $e) {
                $failed++;
                $this->error("FAILED: {$label} - {$e->getMessage()}");
            }
        }

        $this->info("Done. Sent: {$sent}, failed: {$failed}.");

        return $failed ? 1 : 0;
    }
}

