<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmFileTransfer extends Mailable
{
    use Queueable, SerializesModels;
    public $file;
    public $path; 
    /**
     * Create a new message instance.
     * 
     * @return void
     */
    public function __construct($file, $path)
    {
        $this->file = $file;
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.confirmtransfer')
            ->to(config('mapminer.developer_email'));
    }
}
