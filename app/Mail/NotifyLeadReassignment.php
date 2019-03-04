<?php

namespace App\Mail;

use App\Address;
use App\Branch;
use App\Person;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyLeadReassignment extends Mailable
{
    use Queueable, SerializesModels;
    public $address;
    public $branch;
    public $person;
    public $sender;
    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct(Address $address, Branch $branch,Person $person,Person $sender)
    {
        $this->address = $address;
        $this->branch = $branch;
        $this->person = $person; 
        $this->sender = $sender;       
    
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
            
            return $this->markdown('emails.leadreassignmentnotify')
            ->to($this->person->userdetails->email, $this->person->postName())->subject('Lead Reassigned');
        

    }
}
