<?php

namespace App\Mail;
use App\Person;
use App\Lead;
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
    public function __construct(Lead $lead, Branch $branch, $emails)
    {
        $this->lead = $lead;
        $this->branch = $branch;
        $this->emails = $emails; 
   
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(\Config::get('leads.test')){
            return $this->markdown('emails.webleadsbranchnotify')->to(auth()->user()->email, auth()->user()->person->postName())->subject('New Web Lead');
        }else{
             return $this->markdown('emails.webleadsbranchnotify')->to($this->emails['email'], $this->emails['name'])->subject('New Web Lead');
        }
           

    }

}