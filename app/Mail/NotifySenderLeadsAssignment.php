<?php

namespace App\Mail;

use App\LeadSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifySenderLeadsAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $leadsource;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, LeadSource $leadsource)
    {
        $this->data = $data;
        $this->leadsource = $leadsource;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.sendersleads')
            ->subject('Branches Notified')
            ->from(config('mail.from'));
    }
}
