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
    public $transferor;
    /**
     * [__construct description]
     * 
     * @param Project $project    [description]
     * @param Person  $person     [description]
     * @param Person  $transferor [description]
     */
    public function __construct(Project $project, Person $person, Person $transferor)
    {
        $this->project = $project;
        $this->person = $person;
        $this->transferor = $transferor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->markdown('emails.projecttransfernotify')
            ->to($this->person->userdetails->email, $this->person->postName())
            ->subject('Project Transferred');
    }
}
