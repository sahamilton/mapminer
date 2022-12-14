<?php

namespace App\Mail;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
    public function __construct(Address $address, Branch $branch, Person $person, Person $sender)
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
            ->to($this->person->userdetails->email, $this->person->postName())
            ->cc([$this->sender->fullEmail()])
            ->subject('Lead Reassigned');
    }
}
