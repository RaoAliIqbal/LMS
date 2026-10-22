<?php

namespace App\Console\Commands;

use App\Mail\ChatbotStudentFollowUpMail;
use App\Models\ChatEscalation;
use App\Support\ChatbotMailAddresses;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;

class SendChatbotFollowUps extends Command
{
    protected $signature   = 'chatbot:send-followups';
    protected $description = 'Send 48-hour follow-up emails to students who submitted chatbot support requests.';

    public function handle(): int
    {
        ChatbotMailAddresses::warnIfMailDriverCannotDeliver();

        // Find pending escalations where:
        //  - status is still 'pending'
        //  - follow-up email has NOT been sent yet
        //  - it's been at least 48 hours since submission
        $records = ChatEscalation::where('status', 'pending')
            ->whereNull('follow_up_sent_at')
            ->where('created_at', '<=', Carbon::now()->subHours(48))
            ->get();

        if ($records->isEmpty()) {
            $this->info('No follow-ups due at this time.');
            return 0;
        }

        $sent  = 0;
        $fails = 0;

        foreach ($records as $escalation) {
            // Stamp BEFORE sending: a fast email-client click (Outlook prefetch, Gmail proxy,
            // ATP scanner) can land on chatbot.followup before the stamp is committed and the
            // controller would otherwise render Invalid. If the stamp succeeds but mail fails,
            // we accept the trade-off — the cron will not retry for this row.
            try {
                $escalation->update(['follow_up_sent_at' => now()]);
            } catch (QueryException $e) {
                $fails++;
                \Log::error('chatbot follow-up stamp failed', [
                    'escalation_id' => $escalation->id,
                    'email'         => $escalation->email,
                    'error'         => $e->getMessage(),
                ]);
                $this->error("Stamp failed for {$escalation->email}: {$e->getMessage()}");
                continue;
            }

            try {
                Mail::to($escalation->email)
                    ->send(new ChatbotStudentFollowUpMail($escalation));

                $sent++;
                $this->info("Follow-up sent to {$escalation->email} (ID: {$escalation->id})");
            } catch (\Throwable $e) {
                $fails++;
                \Log::error('chatbot follow-up mail failed', [
                    'escalation_id' => $escalation->id,
                    'email'         => $escalation->email,
                    'error'         => $e->getMessage(),
                ]);
                $this->error("Failed for {$escalation->email}: {$e->getMessage()}");
            }
        }

        $this->info("Done. Sent: {$sent}, Failed: {$fails}.");
        return 0;
    }
}
