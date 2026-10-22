<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuizEmail extends Mailable
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
        $data = $this->request;

        if ($data['test']['msg'] == 'fail'){
            $subject = "User Failed in a quiz.";
        }else{
            $subject = "User Passed in a quiz.";
        }
        return $this->subject($subject)
            ->view('email.quizEmail',compact('data'))
            ->from('no-reply@professionalonlineeducation.com','no-reply');
    }
}
