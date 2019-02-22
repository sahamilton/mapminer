<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Branch;
use App\Person;
use App\LeadSource;
class NotifyManagersLeadsAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $manager;
    public $branches;
    public $leadsource;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,Person $manager,LeadSource $leadsource,  $branches)
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
         ->to($this->manager['email'], $this->manager['firstname']." ".$this->manager['lastname']);
    }
}