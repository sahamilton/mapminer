<?php

namespace App\Mail;

use App\Models\Branch;
use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendWeeklyOpportunityReminder extends Mailable
{
    use Queueable, SerializesModels;
    public $branch;
    public $manager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Branch $branch, Person $manager)
    {
        $this->branch = $branch;
        $this->manager = $manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.upcomingopportunities')
            ->subject('Closing Opportunities');
    }
}
