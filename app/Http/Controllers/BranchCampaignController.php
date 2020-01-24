<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\Branch;
use App\Person;
use Carbon\Carbon;

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

        $myBranches = $this->branch->whereIn('id', array_keys($this->person->myBranches()))->get();
        
        $campaigns = $this->campaign->current($myBranches->pluck('id')->toArray())->get();
    
       
        if (! $campaigns->count()) {
            return redirect()->back()->withMessage('there are no current sales campaigns for your branches');
        }
        
        if (session('campaign') && session('campaign') != 'all') {
            $campaign = $this->campaign->findOrFail(session('campaign'));
        } else {
            $campaign = $campaigns->first();
            session(['campaign'=>$campaigns->first()->id]);
        }
        
       
        if ($myBranches->count() == 1) {

            return $this->show($campaign, $myBranches->first());
        }

        $branch_ids = $myBranches->pluck('id')->toArray();
        $branches = $this->branch
            ->whereIn('id', $branch_ids)
            ->summaryCampaignStats($campaign)
            ->get();
      
        $servicelines = $campaign->getServicelines();
        $team = $this->campaign->getSalesTeamFromManager($campaign->manager_id, $servicelines);
        //$locations = $this->_getLocationsForMyBranches($campaign, $myBranches);
     
        return response()->view('campaigns.summary', compact('campaign', 'branches', 'campaigns', 'team'));


    }
    /**
     * [change description]
     * 
     * @param Request $request [description]
     * @param Branch  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function change(Request $request)
    {
        
        if (request('campaign')!= 'all') {
                session(['campaign'=>request('campaign_id')]);
        } else {
            session()->forget('campaign');
        }

        return $this->index();
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
     
        $person = $this->person->findOrFail(auth()->user()->person->id);
        $myBranches = $this->person->myBranches($person);
    
        if (! in_array($branch->id, array_keys($myBranches))) {
            return redirect()->back()->withError('That is not one of your branches');
        }

        $campaigns = $this->campaign->whereHas(
            'branches', function ($q) use ($myBranches) {
                $q->whereIn('branch_id', array_keys($myBranches));
            }
        )
        ->current([$branch->id])->get();// else return not valid
        
        $campaign->first()->load('companies', 'branches');
        /* dd($branch->id, $campaign->branches->pluck('id')->toArray());
        if (! in_array($branch->id, $campaign->branches->pluck('id')->toArray())) {
            return redirect()->back()->withError($branch->branchname . ' is not participating in this campaign.');
        }*/
        
        $branch = $this->branch
            ->campaignDetail($campaign)
            ->findOrFail($branch->id);
       
        $views = [
            'offered'=>['title'=>"New Sales Initiative Leads", 'detail'=>''],

            'untouchedLeads'=>['title'=>"Untouched Sales Initiatives Leads", 'detail'=>'Here are the Sales Initiative Leads that you accepted but do not have any activity. Make sure you enter in any activity that has taken place to remove these Leads for the Untouched list.'],
            'workedLeads'=>['title'=>'Worked Leads', 'details'=>'These are your campaign leads'],
            'opportunitiesClosingThisWeek'=>['title'=>"Opportunities to Close this Week", 'detail'=>'Make sure you are updating your Opportunities status. Opportunities should be marked Closed – Won once we have billed the our new customer.'],
            'upcomingActivities'=>['title'=>"Upcoming Activities", 'detail'=>''],
             
        ];
       
        return response()->view('campaigns.branchplanner', compact('campaign', 'campaigns', 'branch', 'views'));

        
    }
    /**
     * [_getBranchCampaignDetailData description]
     * 
     * @param Campaign $campaign [description]
     * @param Branch   $branch   [description]
     * 
     * @return [type]             [description]
     */
    private function _getBranchCampaignDetailData(Campaign $campaign, Branch $branch)
    {
        
        return $this->branch
            
            ->campaignDetail($campaign)
            ->findOrFail($branch->id);
    }
    private function _getBranchCampaignSummaryData(Campaign $campaign)
    {
        $branch_ids = $campaign->branches->pluck('id')->toArray();
        return $this->branch
            ->whereIn('id', $branch_ids)
            ->summaryCampaignStats($campaign)
            ->get();
    }
    

}
