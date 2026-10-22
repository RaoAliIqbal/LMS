<?php

namespace App\Mail;

use App\Models\ChatEscalation;
use App\Support\ChatbotMailAddresses;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatbotFollowUpStudentReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var ChatEscalation */
    public $escalation;

    /** @var string great_news|on_it|admin */
    public $kind;

    public function __construct(ChatEscalation $escalation, $kind)
    {
        $this->escalation = $escalation;
        $this->kind = $kind;
    }

    public function build()
    {
        $cfg = config('settings.chatbot', []);
        $from = ChatbotMailAddresses::envelopeFrom();
        $fromName = $cfg['from_name_student'] ?? 'CCI Support Team';
        $replyTo = ChatbotMailAddresses::staffInbox();

        $variantMap = [
            'great_news' => 'great_news',
            'on_it' => 'on_it',
            'admin' => 'escalated',
        ];
        $variant = isset($variantMap[$this->kind]) ? $variantMap[$this->kind] : 'great_news';

        $subjects = [
            'great_news' => 'Great news!',
            'on_it' => "We're on it!",
            'escalated' => 'Issue Escalated to Administration',
        ];

        return $this->from($from, $fromName)
            ->replyTo($replyTo)
            ->subject($subjects[$variant] ?? 'Update from CCI Support Team')
            ->view('email.chatbot.student_reply')
            ->with(['variant' => $variant]);
    }
}
