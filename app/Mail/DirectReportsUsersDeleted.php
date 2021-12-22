<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DirectReportsUsersDeleted extends Mailable
{
    use Queueable, SerializesModels;
    public $manager;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.reportesdeleted');
    }
}
