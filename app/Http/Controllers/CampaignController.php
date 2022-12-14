<?php

namespace App\Http\Controllers;
use App\Models\Address;
use App\Models\AddressBranch;
use App\Models\Branch;
use App\Models\Company;
use App\Models\SearchFilter;
use App\Models\Campaign;
use App\Models\Serviceline;
use App\Models\SalesOrg;
use App\Models\Addresses;
use App\Models\Person;
use App\Jobs\AssignCampaignLeadsJob;
use App\Jobs\AssignAddressesToCampaignJob; 
use App\Jobs\AssignBranchesToCampaignJob;
use App\Jobs\SendCampaignLaunched;
use App\Http\Requests\CampaignFormRequest;
use App\Jobs\CampaignSummary;

use Carbon\Carbon;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public $address;
    public $branch;
    public $campaign;
    public $company;
    public $person;
    public $vertical;
    public $salesorg;
    public $serviceline;

    /**
     * /
     * 
     * @param Address      $address      [description]
     * @param Branch       $branch       [description]
     * @param Campaign     $campaign     [description]
     * @param Company      $company      [description]
     * @param Person       $person       [description]
     * @param SearchFilter $searchFilter [description]
     * @param SalesOrg     $salesorg     [description]
     * @param Serviceline  $serviceline  [description]
     */
    public function __construct(
        Address $address,
        Branch $branch,
        Campaign $campaign,
        Company $company,
        Person $person,
        SearchFilter $searchFilter,
        SalesOrg $salesorg,
        Serviceline $serviceline
    ) {
        $this->address = $address;
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->company = $company;
        $this->person = $person;
        $this->vertical = $searchFilter;
        $this->salesorg = $salesorg;
        $this->serviceline = $serviceline;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       /* $campaigns = $this->campaign
            ->with('author', 'manager', 'companies', 'vertical')
            ->withCount('branches')
            ->get();
        $calendar = [];*/
        return response()->view('campaigns.index');
    }

    
    /**
     * [create description]
     *
     * @return [type] [description]
     */
    public function create()
    {

        
        $servicelines = $this->_getServicelines();
        $roles = [6=>'svp',7=>'rvp', 3=>'market_manager'];
        // refactor to Person model
        $managers =Person::query()
            ->withRoles(array_keys($roles))
            ->with('userdetails.roles')
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get()
            ->pluck('fullName', 'id')
            ->prepend('All', 'all')
            ->toArray();
        $campaignmanagers = Person::query()
            ->selectRaw("id, concat_ws(' ',firstname, lastname) as name")
            ->withRoles([4])
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->pluck('name', 'id')
            ->toArray();
        

        $companies = Company::query()
            ->orderBy('companyname')
            ->pluck('companyname', 'id')
            ->toArray();
        $industries = $this->vertical->vertical();
        
       
        return response()->view('campaigns.create', compact('managers', 'servicelines', 'companies', 'campaignmanagers', 'industries'));
    }
    /**
     * [store description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function store(CampaignFormRequest $request)
    {
        
        $data = $this->_transformRequest($request);

        $campaign = $this->campaign->create($data);
        $campaign->servicelines()->sync($data['serviceline']);
        if ($data['industries']) {
            $campaign->vertical()->sync($data['industries']);
        }
        if ($data['companies']) {
            $campaign->companies()->sync($data['companies']); 
        }
        
        
        //$this->_assignBranchesToCampaigns($campaign);
        
        return redirect()->route('campaigns.show', $campaign->id);
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
       
        return response()->view('campaigns.show', compact('campaign'));
        
        
    }
    /**
     * [edit description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function edit(Campaign $campaign)
    {
        $verticals = $this->vertical->industrysegments();
        $companies = Company::orderBy('companyname')->pluck('companyname', 'id')->toArray();
        $servicelines = $this->_getServicelines();
        $roles = [6=>'svp',7=>'rvp', 3=>'market_manager'];
        $managers =Person::query()
            ->withRoles(array_keys($roles))
            ->with('userdetails.roles')
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get()
            ->pluck('fullName', 'id')
            ->prepend('All', 'all')
            ->toArray();
        $campaignmanagers = Person::query()
            ->selectRaw("id, concat_ws(' ',firstname, lastname) as name")
            ->withRoles([4])
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->pluck('name', 'id')
            ->toArray();
        $campaign->load('vertical', 'servicelines',  'manager');
        $industries = $this->vertical->vertical(); 
        return response()->view('campaigns.edit', compact('campaign', 'verticals', 'companies', 'managers', 'servicelines', 'campaignmanagers', 'industries'));
    }
    /**
     * [update description]
     * 
     * @param Campaign $campaign [description]
     * @param Request  $request  [description]
     * 
     * @return [type]             [description]
     */
    public function update(Campaign $campaign, CampaignFormRequest $request) 
    {
        
        $data = $this->_transformRequest($request);
      
        $campaign->update($data);
        $campaign->servicelines()->sync($data['serviceline']);
        $campaign->companies()->sync($data['companies']);
        $this->_assignBranchesToCampaigns($campaign);

        return redirect()->route('campaigns.show', $campaign->id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Campaign $campaign [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->back()->withMessage("Campaign Deleted");
    }


    private function _getServicelines()
    {
        if ($this->serviceline->count() > 1) {
            return $servicelines = $this->serviceline
                ->pluck('ServiceLine', 'id')
                ->toArray();
        } else {
            return $servicelines = $this->serviceline->first()->id;
        }
    }
    /**
     * [launch description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function launch(Campaign $campaign)
    {
        // assign leads to branches
        // update address_branch_campaign
        // notify branch managers
        // update status
        // notify job complete
        AssignCampaignLeadsJob::withChain(
            [
                new AssignAddressesToCampaignJob($campaign),
                new AssignBranchesToCampaignJob($campaign),
                new SendCampaignLaunched($campaign),
            ]
        )->dispatch($campaign->id);
       
        
        
        return redirect()->route('campaigns.index')->withMessage($campaign->title .' Campaign launched. You will receive an email when all leads have been assigned.');
       
        
    }
    /**
     * [populateAddressCampaign description]
     * 
     * @return [type] [description]
     */
    public function populateAddressCampaign()
    {
        $campaigns = $this->campaign->with('manager', 'companies')->get();
        foreach ($campaigns as $campaign) {
            foreach ($campaign->companies as $company) {
                AssignAddresesToCampaignJob::dispatch($company, $campaign);
            }
            
        }
        
    }
    /**
     * [_assignBranchesToCampaigns description]
     * @param  Campaign $campaign [description]
     * @return [type]             [description]
     */
    private function _assignBranchesToCampaigns(Campaign $campaign)
    {
        $manager = $campaign->manager;

        $servicelines = $campaign->servicelines->pluck('id')->toArray();
       
        $activebranches = Branch::inServiceLines($servicelines)
            
            ->pluck('id')
            ->toArray();
        $branches = array_intersect($manager->getMyBranches(), $activebranches);
        return $campaign->branches()->sync($branches);
    }
    /**
     * [branchTest description]
     * 
     * @param Campaign $campaign [description]
     * @param Branch   $branch   [description]
     * 
     * @return [type]             [description]
     */
    public function branchTest(Campaign $campaign, Branch $branch)
    {
        // get MBR of branch
        $companies = $campaign->getCompanyLocationsOfCampaign();
        $company_ids = $companies->pluck('id')->toArray();
        // get locations of campaign within MBR
     
        $locations = $this->address
            ->whereHas(
                'assignedToBranch', function ($q) use ($branch) {
                    $q->where('branches.id', $branch->id);
                }
            )
            ->whereIn('company_id', $company_ids)
            ->nearby($branch, 25)
            ->orderBy('distance')
            ->get();
        
        foreach ($locations as $location) {
            
            $allocated = $this->branch
                ->nearby($location, 25, 1)
                ->orderBy('distance')
                ->first();
            
            $data[$allocated->id][]=$location->id;
        }
       
        // get nearest branch to all locations
        // get distance from branch to all locations

    }
    /**
     * [selectReport description]
     * 
     * @param Campaign|null $campaign [description]
     * 
     * @return [type]                  [description]
     */
    public function selectReport(Campaign $campaign = null)
    {
        
        $campaigns = $this->_getActiveCampaigns();

        if ($campaign) {
            $campaign->load('companies', 'servicelines');
        } elseif ($campaigns) {
            $campaign  = $campaigns->first();
            
        }
       
        if (! $campaign) {
            return redirect()->back()->withError("There are no active campaigns to report on.");
        }
        $servicelines = $campaign->servicelines->pluck('id')->toArray();

        $team = $this->_getSalesTeamFromManager($campaign->manager_id, $servicelines);
        return response()->view('campaigns.selectReport', compact('campaigns', 'campaign', 'team'));
    }

    public function select(Campaign $campaign, Person $manager)
    {
        $branches = array_keys($manager->myBranches($manager));
        $servicelines = $campaign->getServicelines();
        $branches = $this->branch->whereIn('id', $branches)->summaryCampaignStats($campaign)->get();

        $team = $this->campaign->getSalesTeamFromManager($manager->id, $servicelines);
        return response()->view('campaigns.managersummary', compact('campaign', 'branches', 'manager', 'team'));
    }
    
    /**
     * [export description]
     * 
     * @param Request  $request  [description]
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function export(Request $request, Campaign $campaign)
    {
        
        CampaignSummary::dispatch($campaign);
        return redirect()->back()->withMessage('Job has been dispatched. Check your email shortly');
    }
    


    

    /**
     * [_getCampaignServicelines description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return Array             [description]
     */
    private function _getCampaignServicelines(Campaign $campaign)
    {
        $campaign->load('servicelines');
        return $campaign->servicelines->pluck('id')->toArray();
    }
    /**
     * [_getActiveCampaigns description]
     * 
     * @return [type] [description]
     */
    private function _getActiveCampaigns()
    {
        return $this->campaign
            ->active()
            ->with('companies', 'servicelines')
            ->get();
    }
    /**
     * [_getCampaignSummaryData description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getCampaignSummaryData(Campaign $campaign)
    {
        $data = $this->_getCampaignData($campaign);
        // open campaigns do not have pre-assigned locations.
        if ($campaign->type != 'open') {
            $data['locations'] = $this->_getSummaryLocations($campaign, $data['companies']);
        }
        
        return $data;
    }
    /**
     * [_getSummaryLocations description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getSummaryLocations(Campaign $campaign, $data)
    {
     
       
        $result['unassigned'] = $data->map(
            function ($company) {
                return $company->unassigned;
            }
        );
        $result['assignable'] = $this->_getBranchAssignableSummary($campaign, $result['unassigned']);
        $result['unassigned'] = $result['unassigned']->flatten()->count();
        $result['assigned'] = $data->map(
            function ($company) {
                return $company->assigned;
            }
        );
        $result['assigned'] = $result['assigned']->flatten()->count();
        
        return $result;
    }
    /**
     * [_getBranchAssignableSummary description]
     * 
     * @param Campaign $campaign [description]
     * @param [type]   $result   [description]
     * 
     * @return array             [description]
     */
    private function _getBranchAssignableSummary(Campaign $campaign, $result) : array
    {
        $data = [];
        $addresses = $result->flatten()->pluck('id')->toArray();
        $assignable = $campaign->getAssignableLocationsofCampaign($addresses, $count = true);
        foreach ($assignable as $branch) {
            $data[$branch->branch] = $branch->assignable;
        }
        return $data;
    }
    /**
     * [_getCampaignData description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getCampaignData(Campaign $campaign) :array
    {
        
       
        $data['branches'] = $this->_getAssignedLeadsForBranches($campaign);
        $data['companies'] = $campaign->getCompanyLocationsOfCampaign($data['branches']);

        return $data;
    }
    /**
     * [_transformRequest description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _transformRequest(Request $request) :array
    {
       
        $data = request()->except(['_token']);
        $data['datefrom'] = Carbon::parse($data['datefrom'])->startOfDay();
        $data['dateto'] = Carbon::parse($data['dateto'])->endOfDay();
        if (request()->has('vertical') && ! request('companies')) {
            $data['companies'] = $this->_getCompaniesInVertical($request);
        } else {
            $data['companies'] = request('companies');
        }

        $data['created_by'] = auth()->user()->id;
        if (! $data['manager_id'] or $data['manager_id']=='all') {
            
            $data['manager_id'] = $this->salesorg->getCapoDiCapo()->id;
        }

        return $data;
        
    }
    /**
     * [_getCompaniesInVertical description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _getCompaniesInVertical(Request $request) :array
    {
        return $this->company->whereIn('vertical', request('vertical'))->pluck('id')->toArray();
    }
   
    
    /**
     * [_getAllLocations description]
     * 
     * @param [type] $companies [description]
     * 
     * @return [type]            [description]
     */
    private function _getAllLocations($companies) :array
    {
        $data['assigned'] = $companies->map(
            function ($company) {
                return $company->assigned;
            }
        )->flatten();
        $data['unassigned'] = $companies->map(
            function ($company) {
                return $company->unassigned;
            }
        )->flatten();
        return $data;
    }
    
    
    /**
     * [_getBranchesWithinServiceArea description]
     * 
     * @param [type] $campaign  [description]
     * @param [type] $companies [description]
     * 
     * @return [type]            [description]
     */
    private function _getBranchesWithinServiceArea($campaign, $companies)
    {
        $locations = $companies->map(
            function ($company) {
                return $company->locations;
            }
        );
        
        $branch_ids = $campaign->branches->pluck('id')->toarray();
      
        $box = $this->address->getBoundingBox($locations->first());
        
        return $this->branch->getWithinMBR($box)
            ->find($branch_ids);
       
    }
    /**
     * [_getAssignedLeadsForBranches description]
     * 
     * @param [type] $campaign [description]
     * 
     * @return [type]           [description]
     */
    private function _getAssignedLeadsForBranches(Campaign $campaign)
    {
        
        $servicelines = $campaign->servicelines->pluck('id')->toArray();
        $branches = $campaign->getCampaignBranches()->pluck('id')->toArray();
        //$branches = $campaign->branches->pluck('id')->toArray();
        $companies = $campaign->companies->pluck('id')->toArray();
        
        return $this->branch->whereIn('id', $branches)
            ->withCount(
                [
                'addresses as workedleads'=>function ($q) use ($companies) {
                    $q->whereIn('company_id', $companies);
                }

                ]
            )->get();
           
        
        return $data;

    }
    /**
     * [_assignBranchLeads description]
     * 
     * @param Campaign $campaign  [description]
     * @param [type]   $locations [description]
     * 
     * @return [type]              [description]
     */
    private function _assignBranchLeads(Campaign $campaign, $locations) 
    {
        
        $branch_ids = $campaign->branches->pluck('id')->toArray();
      
        $assignments = ['unassigned'=>[],'branch'=>[],'location'=>[]];
        
        
        foreach ($locations as $location ) {
            $branch = $this->branch->whereIn('id', $branch_ids)
                ->nearby($location, 25, 1)
                ->get();
            if ($branch->count()) {
                $assignments['location'][$location->id][] = $branch->first()->id;
                $assignments['branch'][$branch->first()->id][] = $location->id;
            
            } else {
                $assignments['unassigned'][] = $location->id;
            }
            
        } 
        
        $assignments['branches'] = $this->branch->whereIn('id', array_keys($assignments['branch']))->get();
        
        return $assignments;
    }

    /**
     * [_getCampaignPeriod description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getCampaignPeriod(Campaign $campaign)
    {
        $period['from'] = $campaign->datefrom;
        $period['to'] = $campaign->dateto;
        return $period;
    }
}
