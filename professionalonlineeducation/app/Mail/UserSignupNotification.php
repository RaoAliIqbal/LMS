<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserSignupNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Canham College - Registration Successful',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-signup',
            with: [
                'user' => $this->user,
                'loginUrl' => url('/login'),
            ],
        );
    }
}

