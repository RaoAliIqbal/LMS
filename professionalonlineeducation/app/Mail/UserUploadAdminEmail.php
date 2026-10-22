<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserUploadAdminEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        return $this->subject('New File Uploaded')
            ->view('email.UserUploadAdminEmail',compact('data'))
            ->from('no-reply@professionalonlineeducation.com','no-reply')
            ->attach(asset($data['file']), [
                'as' => $data['file'],
            ]);
    }
}
