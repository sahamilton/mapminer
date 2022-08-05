<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\User;

class NotifyManagerOfNoLoginsMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $period;


    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Array $period)
    {
        $this->user = $user;
        $this->period = $period;
        

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->markdown('mail.notify-manager-of-no-logins-mail')->subject("Team Members With Recent Mapminer Usage");
    }

    
}
