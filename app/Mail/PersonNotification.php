<?php

namespace App\Mail;

use App\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PersonNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $person;
    public $action;
    public $changes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->person->userdetails->confirmed) {
            return $this->markdown('emails.usernotification')
                ->subject('Welcome to Mapminer')
                ->to($this->person->userdetails->email);
        }

        return false;
    }
}
