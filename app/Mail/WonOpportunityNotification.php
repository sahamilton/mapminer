<?php

namespace App\Mail;

use App\Opportunity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WonOpportunityNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $opportunity;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.opportunities.won');
    }
}
