<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Person;
use App\Branch;
use App\Campaign;

class BranchCampaignReport extends Mailable
{
    use Queueable, SerializesModels;

    public $manager;
    public $branch;
    public $campaign;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Person $manager, Branch $branch, Campaign $campaign)
    {
        $this->manager = $manager;
        $this->branch = $branch;
        $this->campaign = $campaign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('campaigns.emails.branchcampaign')
            ->subject($this->branch->branchname . ' Sale Initiative Planner for the '. $this->campaign->title. ' Campaign');
    }
}
