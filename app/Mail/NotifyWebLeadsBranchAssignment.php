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
    public function __construct(Address $lead, Branch $branch)
    {
        $this->lead = $lead;
        $this->branch = $branch;
     
       

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        foreach($this->branch->manager as $manager)
             return $this->markdown('emails.webleadsbranchnotify')
             ->to($manager->userdetails->email, $manager->postName())->subject('New Lead');
         

    }

}