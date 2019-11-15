<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;
use App\Address;
use App\Branch;
use App\Campaign;
use App\Opportunity;
class CampaignTrackingController extends Controller
{
    public $activity;
    public $address;
    public $branch;
    public $campaign;
    public $opportunity;

    public function __construct(
        Activity $activity,
        Address $address,
        Branch $branch,
        Campaign $campaign,
        Opportunity $opportunity
    ) {
        $this->activity = $activity;
        $this->address = $address;
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->opportunity = $opportunity;
    }
    

    

    /**
     * [show description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function show(Campaign $campaign)
    {
        
        $campaign->load('companies', 'branches');
        
        $branch_ids = $campaign->branches->pluck('id')->toArray();

        $branches = $this->branch->whereIn('id', $branch_ids)->summaryCampaignStats($campaign)->get();
        $servicelines = $campaign->getServicelines();
        $team = $this->campaign->getSalesTeamFromManager($campaign->manager_id, $servicelines);
        $campaigns = $this->campaign->current()->get();
        return response()->view('campaigns.summary', compact('campaign', 'branches', 'team', 'campaigns'));
    }

}
