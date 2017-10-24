<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyManagersLeadsAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $manager;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$manager)
    {
        $this->data = $data;
        $this->manager = $manager;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.managerleads')
         ->subject('New Leads assigned to your team')
         ->to($this->manager['email'], $this->manager['firstname']." ".$this->manager['lastname']);
    }
}