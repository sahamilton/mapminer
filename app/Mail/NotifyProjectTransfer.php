<?php

namespace App\Mail;
use App\Person;
use App\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyProjectTransfer extends Mailable
{
    use Queueable, SerializesModels;
    public $project;
    public $person;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Project $project, Person $person)
    {
        $this->project = $project;
        $this->person = $person;
       
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->markdown('emails.projecttransfernotify')->to($this->person->userdetails->email, $this->person->postName())->subject('Project Transferred');
    }

}