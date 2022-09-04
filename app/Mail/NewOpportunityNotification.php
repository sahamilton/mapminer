<?php

namespace App\Mail;

use App\Models\Opportunity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOpportunityNotification extends Mailable
{
    use Queueable, SerializesModels;


    public $opportunity;
    public $branchManager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $this->branchManager = $this->_getBranchManagerDetails();

        return $this->replyTo($this->branchManager)
         ->subject('New Large Opportunity Created')
         ->markdown('emails.opportunities.new');
    }

    private function _getBranchManagerDetails()
    {
       
  
        $managers =$this->opportunity->branch->branch->manager;
        
        $list = [];
        foreach ($managers as $mgr) {
            
            $list[] = ['name'=>$mgr->fullName(), 'email'=>$mgr->userdetails->email];
            
        }
        return $list;
    }
}
