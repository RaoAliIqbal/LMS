<?php

namespace App\Mail;

use App\Models\ChatEscalation;
use App\Support\ChatbotMailAddresses;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatbotStudentFollowUpMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var ChatEscalation */
    public $escalation;

    /** @var string */
    public $yesUrl;

    /** @var string */
    public $noUrl;

    /** @var string */
    public $notResolvedUrl;

    public function __construct(ChatEscalation $escalation)
    {
        $this->escalation     = $escalation;
        $this->yesUrl         = route('chatbot.followup', [$escalation->token, 'yes']);
        $this->noUrl          = route('chatbot.followup', [$escalation->token, 'needs-help']);
        $this->notResolvedUrl = route('chatbot.followup', [$escalation->token, 'no-help']);
    }

    public function build()
    {
        $cfg = config('settings.chatbot', []);
        $from = ChatbotMailAddresses::envelopeFrom();
        $fromName = $cfg['from_name_student'] ?? 'CCI Support Team';
        $replyTo = ChatbotMailAddresses::staffInbox();

        return $this
            ->from($from, $fromName)
            ->replyTo($replyTo)
            ->subject('Checking in: Is your issue resolved?')
            ->view('email.chatbot.student_followup');
    }
}
