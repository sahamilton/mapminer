<?php

namespace App\Mail;
use App\Person;
use App\Weblead;
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
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Weblead $lead, Branch $branch, $emails)
    {
        $this->lead = $lead;
        $this->branch = $branch;
        $this->emails;        
       
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
            return $this->markdown('emails.webleadsbranchnotify')->to($this->person->userdetails->email, $this->person->postName())->subject('New Web Lead');

    }

}