<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Person;

class EmailConfirmBranchAssignments extends Mailable
{
    use Queueable, SerializesModels;

    public array $branches;

    public $manager;
    public $person;
    public $token;
    public $expiration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $branches, Person $manager)
    {
        
        $this->branches = $branches;
        $this->person = $manager;
        $this->token = $this->person->userdetails->setAccess();
        $this->expiration = $this->person->userdetails->getExpiration($this->token);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.email-confirm-branch-assignments');
    }
}
