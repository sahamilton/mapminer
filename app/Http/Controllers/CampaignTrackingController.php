<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;
use App\Address;
use App\Branch;
use App\Campaign;
use App\Opportunity;
use Excel;
use App\Exports\CampaignSummaryExport;
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
        $branches = $this->_getBranchesInCampaign($campaign);
        $team = $this->_getCampaignBranchTeam($campaign);
        $campaigns = $this->campaign->current()->get();
        return response()->view('campaigns.summary', compact('campaign', 'branches', 'team', 'campaigns'));
    }


    public function export(Campaign $campaign)
    {
        $campaign->load('companies', 'branches');
        $branches = $this->_getBranchesInCampaign($campaign);
       
        return Excel::download(new CampaignSummaryExport($campaign, $branches), $campaign->title.time().'Export.csv');

    }

    private function _getBranchesInCampaign(Campaign $campaign)
    {
        $branch_ids = $campaign->branches->pluck('id')->toArray();
        return $this->branch->whereIn('id', $branch_ids)->summaryCampaignStats($campaign)->get();
    }

    private function _getCampaignBranchTeam(Campaign $campaign)
    {
        $servicelines = $campaign->getServicelines();
        return $this->campaign->getSalesTeamFromManager($campaign->manager_id, $servicelines);
    }

}
