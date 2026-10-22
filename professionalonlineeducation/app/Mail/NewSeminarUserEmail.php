<?php

namespace App\Mail;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSeminarUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $request = $this->request;

        $user = $request['user'];
        $seminar = $request['seminar'];
        return $this->subject($user->FullName.' Seminar Info')
            ->view('email.NewSeminarUser',compact('request','user','seminar'))
            ->from('no-reply@professionalonlineeducation.com','no-reply');


    }
}
