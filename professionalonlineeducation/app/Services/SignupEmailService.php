<?php

namespace App\Services;

use App\Mail\UserSignupNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SignupEmailService
{
    public function send(User $user): bool
    {
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            Log::error('Signup email skipped: invalid recipient', [
                'user_id' => $user->getKey(),
                'email' => $user->email,
            ]);

            return false;
        }

        try {
            $message = Mail::to($user->email, $user->name);

            if ($bcc = config('mail.signup_audit_bcc')) {
                $message->bcc($bcc);
            }

            $message->send(new UserSignupNotification($user));

            Log::info('Signup email sent', [
                'user_id' => $user->getKey(),
                'email' => $user->email,
            ]);

            return true;
        } catch (Throwable $exception) {
            Log::error('Signup email failed', [
                'user_id' => $user->getKey(),
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            report($exception);

            return false;
        }
    }
}

