<?php

namespace App\Mail;
use App\Person;
use App\Branch;
use App\LeadSource;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyLeadsAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $manager;
    public $leadsource;
    public $branch;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,Person $manager,LeadSource $leadsource,Branch $branch){
        $this->data = $data;
        $this->branch = $branch;
        
        $this->manager = $manager;

        $this->leadsource = $leadsource;
       
 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
      
        return $this->from('salesops@trueblue.com','Sales Operations')
        ->markdown('emails.leadsnotify')
                ->subject('New Leads Assigned');
        }


}