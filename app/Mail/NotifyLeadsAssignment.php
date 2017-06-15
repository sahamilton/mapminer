<?php

namespace App\Mail;
use App\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyLeadsAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $team;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$team)
    {
        $this->data = $data;
        $this->team = $team['details'];
       
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->markdown('emails.leadsnotify')->to($this->team->userdetails->email, $this->team->postName())->subject('New Leads');
    }

}
