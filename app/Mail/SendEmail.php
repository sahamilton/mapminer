<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $recipient;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $recipient)
    {
        
        $this->data = $email;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->markdown('emails.email')->subject($this->data['subject'])
            ->from(config('mail.from'))
            ->to($this->recipient->userdetails->email, $this->recipient->postName());
    }
}
