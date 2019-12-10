<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


use App\Branch;
use App\Campaign;

class BranchCampaignReport extends Mailable
{
    use Queueable, SerializesModels;


    public $branch;
    public $campaign;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Branch $branch, Campaign $campaign)
    {

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
        $this->branch = $this->branch
            ->with('manager')
            ->campaignDetail($this->campaign)
            ->find($this->branch->id);
        return $this->markdown('campaigns.emails.branchcampaign')
            ->subject($this->branch->branchname . ' Sale Initiative Planner for the '. $this->campaign->title. ' Campaign');
    }
}
