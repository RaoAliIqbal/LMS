<?php

namespace App\Mail;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderUserEmail extends Mailable
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
        $cart = $request['cart'];
        $courses = Lesson::whereIn('id',$cart)->get();
        return $this->subject('Thank you for Enrolling')
            ->view('email.NewOrderUser',compact('request','user','courses'))
            ->from('no-reply@professionalonlineeducation.com','no-reply');


    }
}
