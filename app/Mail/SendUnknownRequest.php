<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use \App\Inbound;

class SendUnknownRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $content;

    public $inbound;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $content,Inbound $inbound)
    {
        $this->content = $content;
        $this->inbound = $inbound;
      
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->markdown('emails.unknownrequest')
            ->subject('re: Your email');

    }
}
