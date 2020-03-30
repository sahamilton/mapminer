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
    public $views = [
            'offeredLeads'=>['title'=>"New Sales Initiative Leads", 'detail'=>''],
            'untouchedLeads'=>['title'=>"Untouched Sales Initiatives Leads", 'detail'=>'Here are the Sales Initiative Leads that you accepted but do not have any activity. Make sure you enter in any activity that has taken place to remove these Leads for the Untouched list.'],
            'opportunitiesClosingThisWeek'=>['title'=>"Opportunities to Close this Week", 'detail'=>'Make sure you are updating your Opportunities status. Opportunities should be marked Closed – Won once we have billed the our new customer.'],
            'upcomingActivities'=>['title'=>"Upcoming Activities", 'detail'=>''],
             
        ];

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
            ->has('manager')
            ->with('manager')
            ->campaignDetail($this->campaign)
            ->find($this->branch->id);
        foreach ($this->views as $key=>$view) {
            if (isset($this->branch->$key)) {
                return $this->markdown('campaigns.emails.branchcampaign')
                    ->subject($this->branch->branchname . ' Sales Initiative Planner for the '. $this->campaign->title. ' Campaign');
                break;
            }
        }
        return;
    }
}
