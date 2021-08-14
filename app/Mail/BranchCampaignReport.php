<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;
use App\Branch;
use App\Campaign;

class BranchCampaignReport extends Mailable
{
    use Queueable, SerializesModels;


    public $branch;
    public $data;
    public $campaign;
    public $views = [

            
            'workedleads'=>['title'=>"Campaign Leads", 'detail'=>'Here are your leads that are part of this campaign'],
            'leads'=>['title'=>"Untouched Campaign Leads", 'detail'=>'Here your leads that are part of this campaign but have not had any activity during the campaign period. Make sure you enter in any activity that has taken place to remove these leads for this list.'],
            'opportunitiesClosingThisWeek'=>['title'=>"Opportunities to Close this Week", 'detail'=>'Make sure you are updating your Opportunities status. Opportunities should be marked Closed – Won once we have billed the our new customer.'],
            'upcomingActivities'=>['title'=>"Upcoming Activities", 'detail'=>'Activities scheduled for this week at leads within this campaign'],
             
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
        //dd($this->branch->untouchedLeads);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $this->data = Branch::with('manager')->CampaignDetail($this->campaign, array_keys($this->views))->find($this->branch->id);
        
          
        return $this->markdown('campaigns.emails.branchcampaign')
            ->subject($this->branch->branchname . ' Sales Initiative Planner for the '. $this->campaign->title. ' Campaign');
           
            
        
        
    }
}
