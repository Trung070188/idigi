<?php

namespace App\Notifications;

use App\Channels\InappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;

    public $user;
    public $device_name;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$device_name,$status)
    {
        $this->user=$user;
        $this->device_name=$device_name;
        $this->status=$status;

    }

    /**
         * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [InappChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase ($notifiable)
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user->id,
            'username'=>$this->user->username,
            'device_name'=>$this->device_name,
        ];
    }
}
