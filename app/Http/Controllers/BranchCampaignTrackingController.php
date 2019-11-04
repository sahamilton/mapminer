<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;
use App\Address;
use App\Branch;
use App\Campaign;
use App\Opportunity;
use App\Person;
class CampaignTrackingController extends Controller
{
    public $activity;
    public $address;
    public $branch;
    public $campaign;
    public $opportunity;
    public $person;

    public function __construct(
        Activity $activity,
        Address $address,
        Branch $branch,
        Campaign $campaign,
        Opportunity $opportunity,
        Person $person
    ) {
        $this->activity = $activity;
        $this->address = $address;
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->opportunity = $opportunity
        $this->person = $person;
    }
    
    public function index()
    {
        // check which campaigns are active
        // and available
        // get the following for the first:
        //      Leads offered 
        //      Leads owned
        //      Last periods activities
        //      Upcoming activities
        //      Open opportunities
        //      Stale Leads (no activities in past 4 weeks)
        //      Stale Opportunities (open w/ no activities in past 4 weeks)
    }
    

    /**
     * [show description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function show(Campaign $campaign, Branch $branch = null)
    {
        // get my branches
   
        
        // // check is one of my branches
        // else return not valid

        $campaign->load('companies', 'branches');
        
        if (! in_array($myBranches, $campaign->branches->pluck('id')->toArray()) {
            return redirect()->back()->withError('Your branch(es) are not participating in this campaign.');
        }
        if (count($myBranches) > 0) {
            $branches = $this->_getBranchCampaignSummaryData($campaign);
   
            return response()->view('campaigns.summary', compact('campaign', 'branches'));
        } else {
            $branch = $this->_getBranchCampaignDetailData($campaign, $branch);
   
            return response()->view('campaigns.branchdetail', compact('campaign', 'branches'));
        }
        
    }

    private function _getBranchCampaignSummaryData(Campaign $campaign)
    {
        $branch_ids = $campaign->branches->pluck('id')->toArray();
        return $this->branch
            ->whereIn('id', $branch_ids)
            ->summaryCampaignStats($campaign)
            ->get();
    }


    private function _getBranchCampaignDetailData(Campaign $campaign, Branch $branch)
    {
        
        return $this->branch
            
            ->campaignDetail($campaign)
            ->findOrFail($branch->id);
    }
}
