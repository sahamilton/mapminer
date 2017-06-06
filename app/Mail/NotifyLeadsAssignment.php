<?php

namespace App\Mail;
use App\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyLeadsAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $rep;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Person $rep)
    {
        $this->rep = $rep;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->markdown('salesleads.notify')->subject('New Leads')->to($this->rep->userdetails->email, $this->rep->postName);;
    }

}
