<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\Branch;
use App\Person;
use App\Address;
use App\AddressCampaign;
use Carbon\Carbon;

class BranchCampaignController extends Controller
{
    

    public $branch;
    public $campaign;
    public $addresscampaign;
    public $person;
    public $fields = [
                    "supplied_leads",
                    "offered_leads",
                    "worked_leads",
                    "rejected_leads",
                    "touched_leads",
                    "new_opportunities",
                    "won_opportunities",
                    "opportunities_open",
                    "won_value",
                    "open_value",
                ];
    public $openfields = [
                    
                    "campaign_leads",
     
                    "touched_leads",
                    "new_opportunities",
                    "won_opportunities",
                    "open_opportunities",
                    "won_value",
                    "open_value",
                ];
    /**
     * [__construct description]
     * 
     * @param Branch        $branch   [description]
     * @param Salesactivity $campaign [description]
     */
    public function __construct(
        Branch $branch, 
        Campaign $campaign,
        Person $person,
        AddressCampaign $addresscampaign
    ) {
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->person = $person;
        $this->addresscampaign = $addresscampaign;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        
        if (! session('manager')) {
            $manager = $this->person->where('user_id', auth()->user()->id)->first();
            session(['manager'=>auth()->user()->id]);
        } 
        $manager = $this->person->where('user_id', session('manager'))->first();
    
        $myBranches = $manager->getMyBranches();

        if (! $campaign = $this->campaign->active()->current($myBranches)->with('branches')->first()) {
            return redirect()->back()
                ->withMessage('There are no leads in this campaign currently assigned to your branches');
        }
        if (! $branches = $campaign->branches->whereIn('id', $myBranches)) {
            return redirect()->back()
                ->withMessage('There are no current sales campaigns for your branches');
        }

   
        
        
        
        if (session('campaign') && session('campaign') != 'all') {
            $campaign = $this->campaign->findOrFail(session('campaign'));
        } else {
            
            session(['campaign'=>$campaign->id]);
        }

        if ($branches->count() == 1) {

            return $this->show($campaign, $branches->first());
        } 
        $branch = $branches->first();
        
      
        return response()->view('campaigns.branch', compact('campaign', 'branch'));
    }

    public function store(Request $request)
    {
        $existing = $this->addresscampaign->where('address_id', request('address_id'))->pluck('campaign_id')->toArray();
        $count=0;
        foreach (request('campaign') as $campaign_id) {
            if(! in_array($campaign_id, $existing)) {
                $count++;
               $ac[] = $this->addresscampaign->create(['address_id'=>request('address_id'), 'campaign_id'=>$campaign_id]);
            } 
        }

        foreach ($existing as $campaign) {
            if(! in_array($campaign, request('campaign'))) {
               $count--;
               $ac[] = $this->addresscampaign->delete(['address_id'=>request('address_id'), 'campaign_id'=>$campaign_id]);
            } 
        }
            // check if this address is already part of the campaign
            
        
        return back()->withMessage('Lead added to ' .$count . ' campaigns');
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


    public function delete(Request $request)
    {
        $ac = $this->addresscampaign
        ->where('address_id',request('address_id'))
        ->where('campaign_id',request('campaign_id'))
        ->first();
        $ac->delete();
            
        return back()->withMessage('Lead removed from campaign');
    }
    /**
     * [show description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function show(Campaign $campaign, Branch $branch)
    {
        
        /*$person = $this->person->findOrFail(auth()->user()->person->id);
           

        $myBranches = $this->person->myBranches($person);
    
        if (! in_array($branch->id, array_keys($myBranches))) {
           
            return redirect()->back()->withError('That is not one of your branches');
        }

        $campaigns = $this->campaign->active()->whereHas(
            'branches', function ($q) use ($myBranches) {
                $q->whereIn('branch_id', array_keys($myBranches));
            }
        )
        ->current([$branch->id])->get();// else return not valid
  
        $campaign->load('companies', 'branches');
                
        $branch = $this->branch
            ->when(
                $campaign->type === 'open', function ($q) use($campaign) {
                    $q->openCampaignDetail($campaign);
                }, function ($q) use($campaign) {
                    $q->campaignDetail($campaign);
                }
            )->findOrFail($branch->id);
       
        $views = $this->_getCampaignViews($campaign);
       
        return response()->view('campaigns.branchplanner', compact('campaign', 'campaigns', 'branch', 'views'));*/
         return response()->view('campaigns.branch', compact('campaign', 'branch'));
        
    }

    public function setManager(Campaign $campaign, Request $request)
    {
        
        if (! request()->filled('manager_id')) {
            session()->forget('manager');
            return $this->index();
        }
        if(! auth()->user()->hasRole(['admin'])) {
            $myTeam = $this->person->myTeam()->get()->pluck('id')->toArray();
            if (! in_array(request('manager_id'), $myTeam)) {
                return redirect()->back()->withError('That is not one of your team members');
            }
        }
        // check if this is one of the logged in persons reports
        
        // else redirect back
        session(['manager'=>request('manager_id')]);
        session(['campaign'=>$campaign->id]);
        return $this->index();
        // redirect to this show
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
    /**
     * [_getBranchCampaignSummaryData description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getBranchCampaignSummaryData(Campaign $campaign)
    {
        $branch_ids = $campaign->branches->pluck('id')->toArray();
        return $this->branch
            ->whereIn('id', $branch_ids)
            ->summaryCampaignStats($campaign)
            ->get();
    }
    
    private function _getCampaignViews(Campaign $campaign)
    {
        if ($campaign->type === 'open') {
            return $this->_opencampaignviews();
        } else {
            return $this->_campaignViews();
        }

    }


    private function _campaignviews()
    {
        return  [
            'offeredLeads'=>['title'=>"New Sales Initiative Leads", 'detail'=>'These leads have been offered to your branch.  You must either accept or decline them before you can record any activities or opportunities on them'],

            'untouchedLeads'=>['title'=>"Untouched Sales Initiatives Leads", 'detail'=>'Here are the Sales Initiative Leads that you accepted but do not have any activity. Make sure you enter in any activity that has taken place to remove these Leads for the Untouched list.'],

            'workedLeads'=>['title'=>'Campaign Leads', 'detail'=>'These are your campaign leads'],

            'opportunitiesClosingThisWeek'=>['title'=>"Opportunities to Close this Week", 'detail'=>'Make sure you are updating your Opportunities status. Opportunities should be marked Closed – Won once we have billed the our new customer.'],

            'upcomingActivities'=>['title'=>"Upcoming Activities", 'detail'=>''],
             
        ];
    }

    private function _opencampaignviews()
    {
        return  [
            
            'newLeads'=>['title'=>'New Leads', 'detail'=>'These are your campaign leads that you created for this campaign'],
            'untouchedLeads'=>['title'=>"Untouched Sales Initiatives Leads", 'detail'=>'Here are the Sales Initiative Leads that you added to the campaign  but do not have any activity during the campaign period. Make sure you enter in any activity that has taken place to remove these Leads for the Untouched list.'],
             'workedLeads'=>['title'=>'Campaign Leads', 'detail'=>'These are your campaign leads'],
            

            'opportunitiesClosingThisWeek'=>['title'=>"Opportunities to Close this Week", 'detail'=>'Make sure you are updating your Opportunities status. Opportunities should be marked Closed – Won once we have billed the our new customer.'],

            'upcomingActivities'=>['title'=>"Upcoming Activities", 'detail'=>'Activities for campaign leads that are due this week'],
             
        ];
    }

    private function _getCampaignTeam(Campaign $campaign)
    {
        $campaignTeam = $campaign->getSalesTeamFromManager();
        if( auth()->user()->hasRole('admin', 'sales_operations')) {
            return $campaignTeam;
        }
        $myTeam = $this->person->myTeam()->get();
        return $campaignTeam->intersect($myTeam);
    }
}
