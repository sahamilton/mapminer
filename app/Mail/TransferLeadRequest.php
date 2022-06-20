<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Address;
use App\User;

class TransferLeadRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $address;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Address $address, User $user)
    {
        $this->address = $address->load('claimedByBranch.manager');
        $this->user = $user->load('person.branchesServiced');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo([$this->user->person->fullEmail()])
            ->markdown('mail.transfer-lead-request');
    }
}
