<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmBackup extends Mailable
{
    use Queueable, SerializesModels;
    public $backup;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($filename. $type='database')
    {
        $this->backup = $filename;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.confirmbackup')
            ->to(config('mapminer.developer_email'));
    }
}
