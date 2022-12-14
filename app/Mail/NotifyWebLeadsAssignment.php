<?php

namespace App\Mail;

use App\Models\Branch;
use App\Models\Lead;
use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyWebLeadsAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $lead;
    public $branch;
    public $person;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, Branch $branch, Person $person)
    {
        $this->lead = $lead;
        $this->branch = $branch;
        $this->person = $person;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.webleadsnotify')
            ->to($this->person->userdetails->email, $this->person->postName())
            ->subject('New Lead');
    }
}
