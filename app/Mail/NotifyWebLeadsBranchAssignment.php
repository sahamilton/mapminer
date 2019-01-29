<?php

namespace App\Mail;
use App\Person;
use App\Address;
use App\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyWebLeadsBranchAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $lead;
    public $branch;
    public $emails;
    public $manager;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Address $lead, Branch $branch, Person $manager)
    {
        $this->lead = $lead;
        $this->branch = $branch;
        $this->manager = $manager; 
       

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
             return $this->markdown('emails.webleadsbranchnotify')
             ->to($this->manager->userdetails->email, $this->manager->postName())->subject('New Lead');
         

    }

}