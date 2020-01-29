<?php

namespace App\Mail;

use App\Address;
use App\Branch;
use App\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyWebLeadsBranchAssignment extends Mailable
{
    use Queueable, SerializesModels;
    public $lead;
    public $branch;
    public $emails;
    public $manager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Address $lead, Branch $branch)
    {
        $this->lead = $lead;
        $this->branch = $branch;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        foreach ($this->branch->manager as $this->manager) {
            return $this->markdown('emails.webleadsbranchnotify')
                ->to($this->manager->userdetails->email, $this->manager->postName())
                ->subject('New Lead');
        }
    }
}
