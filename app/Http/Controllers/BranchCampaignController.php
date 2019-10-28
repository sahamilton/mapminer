<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\Branch;
use App\Person;

class BranchCampaignController extends Controller
{
    public $branch;
    public $campaign;
    public $person;
    
    /**
     * [__construct description]
     * 
     * @param Branch        $branch   [description]
     * @param Salesactivity $campaign [description]
     */
    public function __construct(
        Branch $branch, 
        Campaign $campaign,
        Person $person
    ) {
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->person = $person;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        //$this->myBranches = $this->branch->getBranches();
        /**  
        Testing code [description] 
        */
        $person = $this->person->findOrFail(auth()->user()->person->id);
        
        /**
         * End test
         */
        $myBranches = $this->branch->whereIn('id', array_keys($this->person->myBranches($person)))->get();
        
        $campaign = $this->campaign->current($myBranches->pluck('id')->toArray())->get();
       
        if (! $campaign->count()) {
            return redirect()->back()->withMessage('there are no current sales campaigns for your branches');
        }
        $campaign = $campaign->first();
        
        if ($myBranches->count() == 1) {
            return redirect()->route('branchcampaign.show', [$campaign->id, $myBranches->first()->id]);
        }

        $branch_ids = $myBranches->pluck('id')->toArray();
        $branches = $this->branch
            ->whereIn('id', $branch_ids)
            ->summaryCampaignStats($campaign)
            ->get();
       
        //$locations = $this->_getLocationsForMyBranches($campaign, $myBranches);
        return response()->view('campaigns.summary', compact('campaign', 'branches'));


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
        
        $person = $this->person->findOrFail(164);
        $myBranches = $this->person->myBranches($person);
        if (! in_array($branch->id, array_keys($myBranches))) {
            return redirect()->back()->withError('That is not one of your branches');
        }

        $campaigns = $this->campaign->current([$branch->id])->get();// else return not valid

        $campaign->load('companies', 'branches');
        
        if (! in_array($branch->id, $campaign->branches->pluck('id')->toArray())) {
            return redirect()->back()->withError($branch->branchname . ' is not participating in this campaign.');
        }
        
        $branch = $this->branch
            ->campaignDetail($campaign)
            ->findOrFail($branch->id);;
        $views = ['offered', 'leads', 'activities', 'opportunities'];
       
        return response()->view('campaigns.branchplanner', compact('campaign', 'campaigns', 'branch', 'views'));

        
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
