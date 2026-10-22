<?php

namespace App\Console\Commands;

use App\Support\ChatbotMailAddresses;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ChatbotTestOutboundMail extends Command
{
    protected $signature = 'chatbot:test-mail {--to= : Recipient (defaults to CHATBOT manager inbox)}';

    protected $description = 'Send a minimal test email using the same From/staff resolution as the website chatbot (use on production to verify SMTP).';

    public function handle(): int
    {
        ChatbotMailAddresses::warnIfMailDriverCannotDeliver();

        $to = trim((string) $this->option('to'));
        if ($to === '') {
            $to = ChatbotMailAddresses::staffInbox();
        }
        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid --to address or staff inbox is not a valid email. Check CHATBOT_MANAGER_EMAIL in .env.');

            return 1;
        }

        $from = ChatbotMailAddresses::envelopeFrom();
        $this->line('Mail driver: '.config('mail.default'));
        $this->line('Envelope From: '.$from);
        $this->line('Sending test to: '.$to);

        try {
            Mail::raw(
                "This is a test from ".config('app.name')." at ".now()->toDateTimeString().".\r\n\r\n".
                "If you received this, chatbot staff notifications can use the same SMTP path.\r\n",
                function ($message) use ($to, $from) {
                    $message->from($from, 'Chatbot SMTP test')
                        ->to($to)
                        ->subject('[Chatbot] Outbound mail test '.now()->format('Y-m-d H:i'));
                }
            );
        } catch (\Throwable $e) {
            $this->error('Send failed: '.$e->getMessage());
            $this->line($e->getFile().':'.$e->getLine());
            if ($e->getPrevious()) {
                $this->line('Previous: '.$e->getPrevious()->getMessage());
            }

            return 1;
        }

        $this->info('Test message accepted by the mail transport. Check the inbox (and spam) for: '.$to);

        return 0;
    }
}
