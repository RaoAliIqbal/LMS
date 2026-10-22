<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ZoomSessionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lesson;
    public $studentName;
    public $meetingData;

    /**
     * @param  \App\Models\Lesson  $lesson
     * @param  string              $studentName
     * @param  array               $meetingData  [meeting_id, zoom_link, zoom_password, zoom_date, zoom_time, zoom_description]
     */
    public function __construct($lesson, $studentName, array $meetingData)
    {
        $this->lesson      = $lesson;
        $this->studentName = $studentName;
        $this->meetingData = $meetingData;
    }

    public function build()
    {
        return $this->from('no-reply@professionalonlineeducation.com', 'Professional Online Education')
                    ->subject('Live Zoom Session Scheduled: ' . $this->lesson->title)
                    ->view('emails.zoom_session_notification');
    }
}
