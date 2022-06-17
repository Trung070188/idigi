<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use function Sodium\compare;

class SendMailPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;

    /**
     * SendMailPassword constructor.
     * @param $subject
     * @param $body
     */
    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content = $this->content;
        return $this->view('email.send_password', compact('content'))->subject($this->subject);
    }
}
