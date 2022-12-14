<?php

namespace App\Mail;

use App\Models\Branch;
use App\Models\LeadSource;
use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyManagersLeadsAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $manager;
    public $branches;
    public $leadsource;

    /**
     * [__construct description].
     *
     * @param [type]     $data       [description]
     * @param Person     $manager    [description]
     * @param LeadSource $leadsource [description]
     * @param [type]     $branches   [description]
     */
    public function __construct($data, Person $manager, LeadSource $leadsource, $branches)
    {
        $this->data = $data;
        $this->manager = $manager;
        $this->branches = $branches;
        $this->leadsource = $leadsource;
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
            ->to($this->manager['email'], $this->manager['firstname'].' '.$this->manager['lastname']);
    }
}
