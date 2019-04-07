<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmBackup extends Mailable
{
    use Queueable, SerializesModels;
    public $backup;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->backup = $filename;
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
