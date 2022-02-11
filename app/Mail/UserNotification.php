<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class UserNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $action;
    public $changes;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user->load('person');
        
    
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from'))
            ->markdown('emails.usernotification')
            ->to($this->user->email, $this->user->person->fullname())
            ->subject("Welcome to Mapminer");
        
        
    }
}
