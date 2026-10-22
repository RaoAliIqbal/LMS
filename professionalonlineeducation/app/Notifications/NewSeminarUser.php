<?php

namespace App\Notifications;

use App\Http\Helpers\General\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\NewSeminarUserEmail as Mailable;

class NewSeminarUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->data = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $billingEmail = Helper::billingEmailFromNotificationData($this->data, $notifiable);
        $mailable = (new Mailable($this->data))->to($billingEmail);

        if ($notifiable && ! empty($notifiable->email)
            && strcasecmp($billingEmail, $notifiable->email) !== 0) {
            $mailable->cc($notifiable->email);
        }

        return $mailable;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
