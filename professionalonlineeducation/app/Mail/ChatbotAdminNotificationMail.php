<?php

namespace App\Mail;

use App\Models\ChatEscalation;
use App\Support\ChatbotMailAddresses;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatbotAdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var ChatEscalation */
    public $escalation;

    public function __construct(ChatEscalation $escalation)
    {
        $this->escalation = $escalation;
    }

    public function build()
    {
        $label = $this->escalation->typeLabel();
        $cfg = config('settings.chatbot', []);
        $from = ChatbotMailAddresses::envelopeFrom();
        $name = $cfg['from_name_escalations'] ?? 'Support Bot Escalations';

        return $this
            ->from($from, $name)
            ->replyTo($this->escalation->email, ChatbotMailAddresses::sanitizeDisplayName($this->escalation->name))
            ->subject("New Support Request: {$label} — {$this->escalation->name}")
            ->view('email.chatbot.admin_notification');
    }
}
