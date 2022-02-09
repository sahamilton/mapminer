<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class ManagerNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $manager;
    public $branches;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user->load('person.reportsTo.userdetails');
        $this->manager = $this->user->person->reportsTo;
        $this->branches = $this->user->person->branchesServiced->pluck('branchname')->toArray();
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from(config('mail.from'))
            ->markdown('mail.manager-notification')
            ->to($this->manager->userdetails->email, $this->manager->fullname())
            ->subject($this->user->person->fullName() . " added to Mapminer");
    }
}