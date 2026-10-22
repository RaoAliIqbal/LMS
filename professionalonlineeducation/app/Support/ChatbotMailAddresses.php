<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

/**
 * Resolves From / staff addresses for chatbot mail.
 *
 * On most production SMTP (cPanel, many Office365 connectors), the envelope From
 * must match the authenticated mailbox (MAIL_USERNAME). By default we align chatbot
 * mail with that mailbox whenever MAIL_USERNAME is a valid email.
 */
class ChatbotMailAddresses
{
    /**
     * MAIL_USERNAME when it is a valid email (API-key style usernames return null).
     */
    public static function smtpAuthMailbox(): ?string
    {
        $u = trim((string) data_get(config('mail'), 'mailers.smtp.username', ''));
        if ($u !== '' && filter_var($u, FILTER_VALIDATE_EMAIL)) {
            return $u;
        }

        return null;
    }

    /**
     * Strip CR/LF from display names so Reply-To / From name headers cannot break SMTP.
     */
    public static function sanitizeDisplayName(?string $name): string
    {
        $name = str_replace(["\r", "\n"], ' ', (string) $name);
        $name = trim($name);

        return $name !== '' ? $name : 'Student';
    }

    /**
     * Log if mail cannot reach real inboxes (common mis-deploy).
     */
    public static function warnIfMailDriverCannotDeliver(): void
    {
        $driver = (string) config('mail.default', 'smtp');
        if (in_array($driver, ['log', 'array'], true)) {
            Log::warning('chatbot: MAIL_MAILER is "'.$driver.'" — emails are not sent to real addresses. Set MAIL_MAILER=smtp and SMTP credentials on the server.', [
                'mail_default' => $driver,
            ]);
        }
    }

    public static function envelopeFrom(): string
    {
        $candidates = self::envelopeFromCandidates();

        return $candidates[0] ?? 'noreply@professionalonlineeducation.com';
    }

    /**
     * From addresses to try (cPanel requires From to match the authenticated mailbox).
     *
     * @return string[]
     */
    public static function envelopeFromCandidates(): array
    {
        $candidates = [];
        $cfg = config('settings.chatbot', []);
        $useCustom = ! empty($cfg['use_custom_chatbot_from']);
        $smtpMailbox = self::smtpAuthMailbox();
        $alignWithSmtp = ! empty($cfg['force_smtp_username_as_envelope_from']);

        if ($useCustom) {
            $from = trim((string) ($cfg['from_address'] ?? ''));
            if ($from !== '' && filter_var($from, FILTER_VALIDATE_EMAIL)) {
                $candidates[] = $from;
            }
        }

        if ($smtpMailbox !== null) {
            if ($alignWithSmtp || $useCustom === false) {
                array_unshift($candidates, $smtpMailbox);
            } else {
                $candidates[] = $smtpMailbox;
            }
        }

        $from = trim((string) config('mail.from.address', ''));
        if ($from !== '' && filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $candidates[] = $from;
        }

        $from = trim((string) config('settings.admin_email', ''));
        if ($from !== '' && filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $candidates[] = $from;
        }

        $candidates[] = 'noreply@professionalonlineeducation.com';

        return array_values(array_unique(array_filter($candidates)));
    }

    public static function staffInbox(): string
    {
        $primary = trim((string) config('settings.chatbot.manager_email', ''));
        if ($primary !== '' && filter_var($primary, FILTER_VALIDATE_EMAIL)) {
            return $primary;
        }
        $fallback = trim((string) config('settings.chatbot.support_email', ''));
        if ($fallback !== '' && filter_var($fallback, FILTER_VALIDATE_EMAIL)) {
            return $fallback;
        }

        return 'mgr@professionalonlineeducation.com';
    }
}
