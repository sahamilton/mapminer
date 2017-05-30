<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendManagersCampaignMail extends Mailable
{
    use Queueable, SerializesModels;
    public $manager;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $manager)
    {
        $this->manager= $manager;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->markdown('salesactivity.managercampaignemail')
         ->subject($this->data['activity']->title)
         ->to($this->manager['email'],$this->manager['firstname'] . " " . $this->manager['lastname']);
    }
}
