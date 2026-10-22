<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseCompletionUserEmail extends Mailable
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
//        cat 2 to 10 = type two
//        cat 17 = type three
//        cat 34 = type five
//        cat 38 = type four
//        cat 42 = type one
//        CAT46  = type Six
//        CAT28-30 35-37 39 41 43 45  = type seven
//        CAT01  = type eight
//        CAT42  = type nine


        $typeOne = [45];
        $typeTwo = [22,23,24,25,26,27,28,29,31];
        $typeThree = [34];
        $typeFour = [50];
        $typeFive = [38];
        $typeSix = [49];
        $typeSeven = [35,36,37,42,44,46,48];
        $typeEight = [16];
        $typeNine = [45];
        $lessonId = $data['lesson']->id;
        if (in_array($lessonId, $typeOne, true)){
            $view = 'email.CourseCompletionUserTypeOne';
        }else if (in_array($lessonId, $typeTwo, true)){
            $view = 'email.CourseCompletionUserTypeTwo';
        }else if (in_array($lessonId, $typeThree, true)){
            $view = 'email.CourseCompletionUserTypeThree';
        }else if (in_array($lessonId, $typeFour, true)){
            $view = 'email.CourseCompletionUserTypeFour';
        }else if (in_array($lessonId, $typeFive, true)){
            $view = 'email.CourseCompletionUserTypeFive';
        }else if (in_array($lessonId, $typeSix, true)){
            $view = 'email.CourseCompletionUserTypeSix';
        }else if (in_array($lessonId, $typeSeven, true)){
            $view = 'email.CourseCompletionUserTypeSeven';
        }else if (in_array($lessonId, $typeEight, true)){
            $view = 'email.CourseCompletionUserTypeEight';
        }else if (in_array($lessonId, $typeNine, true)){
            $view = 'email.CourseCompletionUserTypeNine';
        }else{
            return true;
        }


        return $this->subject('Course Completion')
            ->view($view,compact('data'))
            ->from('no-reply@professionalonlineeducation.com','no-reply');
    }
}
