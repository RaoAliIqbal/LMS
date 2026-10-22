<?php

namespace App\Mail;

use App\Models\ChatEscalation;
use App\Support\ChatbotMailAddresses;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatbotInternalPriorityMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var ChatEscalation */
    public $escalation;

    /** @var string */
    public $issueTypeLabel;

    public function __construct(ChatEscalation $escalation, $issueTypeLabel)
    {
        $this->escalation = $escalation;
        $this->issueTypeLabel = $issueTypeLabel;
    }

    public function build()
    {
        $cfg = config('settings.chatbot', []);
        $from = ChatbotMailAddresses::envelopeFrom();
        $name = $cfg['from_name_escalations'] ?? 'Support Bot Escalations';

        $subject = 'Priority Escalation: '.$this->issueTypeLabel.' - '.$this->escalation->name;

        return $this->from($from, $name)
            ->replyTo($this->escalation->email, ChatbotMailAddresses::sanitizeDisplayName($this->escalation->name))
            ->subject($subject)
            ->view('email.chatbot.admin_priority')
            ->with(['issueTypeLabel' => $this->issueTypeLabel]);
    }
}
