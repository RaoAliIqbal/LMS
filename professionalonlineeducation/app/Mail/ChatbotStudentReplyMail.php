<?php

namespace App\Mail;

use App\Models\ChatEscalation;
use App\Support\ChatbotMailAddresses;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatbotStudentReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var ChatEscalation */
    public $escalation;

    /** @var string great_news | on_it | escalated */
    public $variant;

    public function __construct(ChatEscalation $escalation, $variant)
    {
        $this->escalation = $escalation;
        $this->variant    = $variant;
    }

    public function build(): self
    {
        $subjects = [
            'great_news' => 'Great news!',
            'on_it'      => "We're on it!",
            'escalated'  => 'Issue Escalated to Administration',
        ];

        $cfg = config('settings.chatbot', []);
        $from = ChatbotMailAddresses::envelopeFrom();
        $fromName = $cfg['from_name_student'] ?? 'CCI Support Team';
        $replyTo = ChatbotMailAddresses::staffInbox();

        return $this
            ->from($from, $fromName)
            ->replyTo($replyTo)
            ->subject($subjects[$this->variant] ?? 'Update from CCI Support Team')
            ->view('email.chatbot.student_reply')
            ->with(['variant' => $this->variant]);
    }
}
