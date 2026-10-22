<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = trim($request->input('email'));
        $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();
        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'No account was found with this email. Please register first or call 1-888-900-3382.',
                ]);
        }

        if ((int) $user->status !== 1) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'This account is disabled. Please call 1-888-900-3382.',
                ]);
        }

        // Use the stored address so the token/notification match the user row exactly.
        $credentials = ['email' => $user->email];
        $response = null;
        $lastError = null;

        foreach ($this->mailerCandidates() as $mailer) {
            try {
                config(['mail.default' => $mailer]);
                $response = $this->broker()->sendResetLink($credentials);
                if ($response === Password::RESET_LINK_SENT || $response === Password::RESET_THROTTLED) {
                    break;
                }
            } catch (\Throwable $e) {
                $lastError = $e;
                \Log::warning('Password reset email failed', [
                    'mailer' => $mailer,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                // The token was stored before sending; drop it so the user can retry
                // immediately instead of hitting the 60s throttle.
                $this->broker()->deleteToken($user);
            }
        }

        if ($response === Password::RESET_LINK_SENT) {
            return $this->sendResetLinkResponse($request, $response);
        }

        if ($response === Password::RESET_THROTTLED) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'A reset link was just sent. Please wait a minute, check your inbox and spam folder, then try again.']);
        }

        \Log::error('Password reset email failed on all mailers', [
            'user_id' => $user->id,
            'error' => $lastError ? $lastError->getMessage() : $response,
        ]);

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'We could not send the reset email right now. Please call 1-888-900-3382 and we will reset your password manually.');
    }

    private function mailerCandidates(): array
    {
        $original = (string) config('mail.default', 'smtp');
        $list = in_array($original, ['log', 'array'], true) ? [] : [$original];
        $list[] = 'smtp';
        $list[] = 'sendmail';
        // On a local machine SMTP is usually unreachable: the log driver writes the
        // reset link to storage/logs so the flow can still be tested.
        if (app()->environment('local')) {
            $list[] = 'log';
        }

        return array_values(array_unique($list));
    }
}
