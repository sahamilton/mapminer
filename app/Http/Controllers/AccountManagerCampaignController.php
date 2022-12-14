<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Activity;
use App\Models\Address;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Opportunity;
use App\Models\Person;
class AccountManagerCampaignController extends Controller
{
    public $activity;
    public $address;
    public $branch;
    public $campaign;
    public $company;
    public $opportunity;
    public $person;

    public function __construct(
        Activity $activity,
        Address $address,
        Branch $branch,
        Campaign $campaign,
        Company $company,
        Opportunity $opportunity,
        Person $person
    ) {
        $this->activity = $activity;
        $this->address = $address;
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->company = $company;
        $this->opportunity = $opportunity;
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
    public function show(Campaign $campaign, Company $company = null)
    {
        $campaign->load('companies', 'branches');
        //
        //get my accounts
        if (! in_array($myBranches, $campaign->branches->pluck('id')->toArray())) {
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
