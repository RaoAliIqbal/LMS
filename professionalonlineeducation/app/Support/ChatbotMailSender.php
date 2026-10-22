<?php

namespace App\Support;

use App\Mail\ChatbotStaffNotificationMail;
use App\Models\ChatEscalation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ChatbotMailSender
{
    /**
     * Send staff notification for a saved escalation.
     * Tries sendmail first on production (cPanel), then SMTP, then plain-text fallback.
     */
    public static function notifyStaff(ChatEscalation $escalation): bool
    {
        $to = ChatbotMailAddresses::staffInbox();
        if ($to === '' || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Log::error('chatbot: no valid staff inbox', ['id' => $escalation->id]);

            return false;
        }

        ChatbotMailAddresses::warnIfMailDriverCannotDeliver();

        foreach (self::mailerOrder() as $mailer) {
            if (! self::mailerConfigured($mailer)) {
                continue;
            }

            if (self::trySendMailable($escalation, $to, $mailer)) {
                Log::info('chatbot: staff notification sent', [
                    'id' => $escalation->id,
                    'to' => $to,
                    'mailer' => $mailer,
                ]);

                return true;
            }
        }

        foreach (self::mailerOrder() as $mailer) {
            if (! self::mailerConfigured($mailer)) {
                continue;
            }

            if (self::trySendRaw($escalation, $to, $mailer)) {
                Log::info('chatbot: staff notification sent (raw fallback)', [
                    'id' => $escalation->id,
                    'to' => $to,
                    'mailer' => $mailer,
                ]);

                return true;
            }
        }

        Log::error('chatbot: all mail attempts failed for staff notification', [
            'id' => $escalation->id,
            'to' => $to,
            'from' => ChatbotMailAddresses::envelopeFrom(),
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_username' => ChatbotMailAddresses::smtpAuthMailbox(),
        ]);

        return false;
    }

    /**
     * Mailers to try, in order. cPanel: sendmail often works when SMTP auth fails.
     *
     * @return string[]
     */
    protected static function mailerOrder(): array
    {
        $order = [];

        $forced = trim((string) env('CHATBOT_MAILER', ''));
        if ($forced !== '') {
            $order[] = $forced;
        }

        $default = (string) config('mail.default', env('MAIL_DRIVER', 'smtp'));

        if (! app()->environment('local') && filter_var(env('CHATBOT_PREFER_SENDMAIL', true), FILTER_VALIDATE_BOOLEAN)) {
            $order[] = 'sendmail';
        }

        if ($default !== '') {
            $order[] = $default;
        }

        if (app()->environment('local')) {
            $order[] = 'log';
        } elseif ($default !== 'sendmail') {
            $order[] = 'sendmail';
        }

        return array_values(array_unique(array_filter($order)));
    }

    protected static function mailerConfigured(string $mailer): bool
    {
        if ($mailer === 'sendmail') {
            return true;
        }

        return config('mail.mailers.'.$mailer) !== null;
    }

    protected static function trySendMailable(ChatEscalation $escalation, string $to, string $mailer): bool
    {
        try {
            Mail::mailer($mailer)->to($to)->send(new ChatbotStaffNotificationMail($escalation));

            return true;
        } catch (\Throwable $e) {
            $prev = $e->getPrevious();
            Log::warning('chatbot: mailable send failed', [
                'id' => $escalation->id,
                'to' => $to,
                'from' => ChatbotMailAddresses::envelopeFrom(),
                'mailer' => $mailer,
                'message' => $e->getMessage(),
                'previous' => $prev ? $prev->getMessage() : null,
            ]);

            return false;
        }
    }

    protected static function trySendRaw(ChatEscalation $escalation, string $to, string $mailer): bool
    {
        $cfg = config('settings.chatbot', []);
        $fromName = $cfg['from_name_escalations'] ?? 'Support Bot Escalations';
        $subject = $escalation->type === 'instructor'
            ? 'Student Requesting Instructor Support'
            : ($escalation->type === 'customer_service'
                ? 'Student Customer Service Request — Website Chatbot'
                : 'Student IT Support Request — Website Chatbot');

        $body = "New Support Request — CCI Chatbot\r\n\r\n"
            ."Type: ".$escalation->typeLabel()."\r\n"
            ."Name: ".$escalation->name."\r\n"
            ."Email: ".$escalation->email."\r\n"
            ."Phone: ".($escalation->phone ?: 'Not provided')."\r\n"
            ."Course: ".($escalation->course_name ?: 'Not specified')."\r\n\r\n"
            ."Issue:\r\n".($escalation->issue ?: 'No description provided.')."\r\n";

        foreach (ChatbotMailAddresses::envelopeFromCandidates() as $from) {
            try {
                Mail::mailer($mailer)->raw($body, function ($message) use ($to, $from, $fromName, $subject, $escalation) {
                    $message->from($from, $fromName)
                        ->replyTo($escalation->email, ChatbotMailAddresses::sanitizeDisplayName($escalation->name))
                        ->to($to)
                        ->subject($subject);
                });

                return true;
            } catch (\Throwable $e) {
                Log::warning('chatbot: raw send failed', [
                    'mailer' => $mailer,
                    'from' => $from,
                    'to' => $to,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return false;
    }
}
