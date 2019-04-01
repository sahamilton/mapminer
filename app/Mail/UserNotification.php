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
        $this->user = $user->with('person')->first();
        //$this->person = Person::where('id','=',$this->user->person_id)->first();
    
        dd($this->user);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    
        if ($this->user->status == 'active') {
            return $this->markdown('emails.usernotification')->to($this->user->email);
        }
        //return $this->markdown('emails.usernotification');
    }
}
