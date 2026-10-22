<?php

namespace App\Mail;

use App\Models\ChatEscalation;
use App\Support\ChatbotMailAddresses;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatbotAdminPriorityMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var ChatEscalation */
    public $escalation;

    /** @var string */
    public $triggerVariant;

    public function __construct(ChatEscalation $escalation, $triggerVariant)
    {
        $this->escalation     = $escalation;
        $this->triggerVariant = $triggerVariant;
    }

    public function build(): self
    {
        $label = $this->escalation->typeLabel();
        $subject = "Priority Escalation: {$label} — {$this->escalation->name}";

        $cfg = config('settings.chatbot', []);
        $from = ChatbotMailAddresses::envelopeFrom();
        $name = $cfg['from_name_escalations'] ?? 'Support Bot Escalations';

        return $this->from($from, $name)
            ->replyTo($this->escalation->email, ChatbotMailAddresses::sanitizeDisplayName($this->escalation->name))
            ->subject($subject)
            ->view('email.chatbot.admin_priority')
            ->with(['issueTypeLabel' => $this->escalation->typeLabel()]);
    }
}
