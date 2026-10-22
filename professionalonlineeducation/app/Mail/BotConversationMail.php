<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BotConversationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $message_arr = null;
    public function __construct($message_arr)
    {
        $this->message_arr = $message_arr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $html = '';
        
        foreach($this->message_arr AS $key=>$arr){

            if($key%2 == 1){
                $html .= "<p>Bot : {$arr}</p>";
            }else{
                $html .= "<p>User : {$arr}</p>";
            }
        }
        return $this->view('email.BotConversationMail', compact('html'))->subject('Bot Conversation Mail');
    }
}
