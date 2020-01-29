<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        $this->manager = $manager;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.managerscampaign')
            ->subject('New Sales Campaign for your team')
            ->to($this->manager['email'], $this->manager['firstname'].' '.$this->manager['lastname']);
    }
}
