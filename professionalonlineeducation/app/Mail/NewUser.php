<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $tab = "<table><tr><td>".$this->user->FullName."</td><td>".$this->user->email."</td></tr></table>";

        return $this->subject('Welcome to Professional Online Education')
            ->view('email.adminNotification',compact('user','tab'))
            ->from('no-reply@professionalonlineeducation.com','no-reply');
    }
}
