<?php

namespace App\Mail;

use App\User;
use App\Person;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyBranchAssignments extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $person;
    public $token;
    public $message;
    public $expiration;
    public $cid;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Person $person, $message,$cid)
    {
        $this->person = $person;
        $this->message = $message;
        // we have to create a unique new time expire token
        $this->user = User::findOrFail($person->user_id);
        $this->token = $this->user->setAccess();
        // and add the campaign id
        $this->cid = $cid;
        $this->expiration = Carbon::now()->addHours('48');
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.branches.confirmation')
            ->subject('Please confirm your branch associations')
            ->withSwiftMessage(function($message) {
                $headers = $message->getHeaders();
                $headers->addTextHeader("X-Mailgun-Variables", '{"type": "branch-confirmation"}');
                $headers->addTextHeader("X-Mailgun-Tag", "branch-confirmation");
            });
    }
}
