<?php

namespace App\Http\Controllers;
use App\Address;
use App\Branch;
use App\Company;
use App\SearchFilter;
use App\Campaign;
use App\Serviceline;
use App\SalesOrg;
use App\Addresses;
use App\Person;
use App\Jobs\AssignCampaignLeads;
use App\Http\Requests\CampaignFormRequest;

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
        $campaigns = $this->campaign
            ->with('author', 'manager', 'companies', 'vertical')
            ->withCount('branches')
            ->get();
        $calendar = \Calendar::addEvents($campaigns);
        return response()->view('campaigns.index', compact('campaigns', 'calendar'));
    }

    
    /**
     * [create description]
     * 
     * @return [type] [description]
     */
    public function create()
    {
        

        $verticals = $this->vertical->industrysegments();
        $companies = $this->company->whereIn('accounttypes_id', [1,4])->whereHas('locations')->orderBy('companyname')->get();
        $servicelines = $this->serviceline->all();
        $roles = [6=>'svp',7=>'rvp', 3=>'market_manager'];
        // refactor to Person model
        $managers = $this->person
            ->withRoles(array_keys($roles))
            ->with('userdetails.roles')
            ->orderBy('lastname')
            ->orderBy('firstname')->get();
        
       
        return response()->view('campaigns.create', compact('verticals', 'companies', 'managers', 'servicelines'));
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
        $servicelines = request('serviceline');
        $branches = $this->_getbranchesFromManager($servicelines, $data['manager_id']);
        
        $campaign = $this->campaign->create($data);
        $campaign->branches()->sync($branches);
        $campaign->servicelines()->sync($data['serviceline']); 
        
        if (isset($data['vertical'])) {
            $campaign->vertical()->sync($data['vertical']);
           
        }
        //$data['branches'] = $this->_getCampaignData($campaign);
        //$campaign->branches()->sync(array_keys($data['branches']['assignments']['branch']));
        $campaign->companies()->sync($data['companies']);
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
        if ($campaign->status == 'planned') {

           
            $campaign->load('vertical', 'servicelines', 'branches', 'companies.managedBy', 'manager', 'team', 'documents');
            
            $data = $this->_getCampaignSummaryData($campaign);
            return response()->view('campaigns.show', compact('campaign', 'data'));
        }
       
        return redirect()->route('campaigns.track', $campaign->id);
        
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
        $companies = $this->company->whereIn('accounttypes_id', [1,4])->whereHas('locations')->orderBy('companyname')->get();
        $servicelines = $this->serviceline->all();
        $roles = [6=>'svp',7=>'rvp', 3=>'market_manager'];
        $managers = $this->person
            ->withRoles(array_keys($roles))
            ->with('userdetails.roles')
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();
        $campaign->load('vertical', 'servicelines',  'manager'); 
        return response()->view('campaigns.edit', compact('campaign', 'verticals', 'companies', 'managers', 'servicelines'));
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
        $servicelines = $this->_getCampaignServicelines($campaign);
        $data['branches'] = $this->_getbranchesFromManager($servicelines, $data['manager_id']);

        $campaign->branches()->sync($data['branches']); 

        $campaign->load('branches');

        $campaign->servicelines()->sync($data['serviceline']); 
  
        if (isset($data['vertical'])) {
            $campaign->vertical()->sync($data['vertical']);
        }
       
        $campaign->companies()->sync($data['companies']);
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

    /**
     * [launch description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function launch(Campaign $campaign)
    {
        $campaign->update(['status'=> 'launched']);
        $campaign->load('vertical', 'servicelines', 'branches', 'companies.managedBy', 'manager', 'team');
        $data = $this->_getCampaignData($campaign);
        foreach ($data['assignments']['branch'] as $branch_id=>$addresses) {
            AssignCampaignLeads::dispatch($branch_id, $addresses);
        }
        
        return redirect()->route('campaigns.index')->withMessage($campaign->title .' Campaign launched');
       
        
    }

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
        
        $servicelines = $this->_getCampaignServicelines($campaign);
        if (! request('manager_id')) {
            $manager_id = $campaign->manager_id;
        } else {
            $manager_id = request('manager_id');
        }
        $branches = $this->_getbranchesFromManager($servicelines, $manager_id);
        
        $manager = $this->person->findOrFail($manager_id);
        $branches = $this->branch->whereIn('id', $branches)->summaryCampaignStats($campaign)->get();

        $team = $this->campaign->getSalesTeamFromManager($campaign->manager_id, $servicelines);
        return response()->view('campaigns.managersummary', compact('campaign', 'branches', 'manager', 'team'));

        // get summaryStats from campaign with branches
        // 
        // Export report
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
     * [_getSalesTeamFromManager description]
     * 
     * @param [type] $manager_id  [description]
     * @param [type] $serviceline [description]
     * 
     * @return [type]              [description]
     */
    /*private function _getSalesTeamFromManager($manager_id, $serviceline)
    {
        return $this->person->whereId([$manager_id])->firstOrFail()->descendantsAndSelf()
            ->whereHas(
                'userdetails.roles', function ($q) {
                        $q->whereIn('roles.id', ['3','6','7']);
                }
            )
            ->with(
                ['branchesServiced'=>function ($q) use ($serviceline) {
                    $q->whereHas(
                        'servicelines', function ($q1) use ($serviceline) {
                            $q1->whereIn('id', $serviceline);
                        }
                    );
                }
                ]
            )
            ->orderBy('lastname')
            ->orderBY('firstname')
            ->get();
    }*/
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
        $data['locations']['unassigned'] = $data['locations']['unassigned']->count();
        $data['locations']['assigned'] = $data['locations']['assigned']->count();
        return $data;
    }
    /**
     * [_getCampaignData description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getCampaignData(Campaign $campaign)
    {
        
        // get companies in campaign with locations within branch box
        $data['companies'] = $this->_getLocationsOfCompaniesInCampaign($campaign);

        // extract the locations into assigned and unassigned
        $data['locations'] = $this->_getAllLocations($data['companies']);
        $data['branches'] = $this->_getBranchesWithinServiceArea($campaign, $data['locations']['unassigned']);
        // get the branches in campaign that are already servicing the companies locations
        $data['branchesw'] =  $this->_getAssignedLeadsForBranches($campaign, $data['locations']['assigned']);
        // assign the unassigned locations 
        
        //$data['assignments'] = $this->_assignBranchLeads($data['locations']['unassigned'], $campaign);
        
        // Merge the branches that could have locations with those that do
        
        
    
        return $data;
    }
    /**
     * [_transformRequest description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    private function _transformRequest(Request $request)
    {
       
        $data = request()->except(['_token']);
        $data['datefrom'] = Carbon::parse($data['datefrom'])->startOfDay();
        $data['dateto'] = Carbon::parse($data['dateto'])->endOfDay();
        if (request()->has('vertical')) {
            $data['companies'] = $this->_getCompaniesInVertical($request);
        } else {
            $data['companies'] = request('companies');
        }
        $data['created_by'] = auth()->user()->id;
        if (! $data['manager_id']) {
            
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
    private function _getCompaniesInVertical(Request $request)
    {
        return $this->company->whereIn('vertical', request('vertical'))->pluck('id')->toArray();
    }
    /**
     * [_getbranchesFromManager description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getbranchesFromManager(Array $servicelines, $manager_id)
    {
        
        $managers = $this->campaign->getSalesTeamFromManager($manager_id, $servicelines);

        $branches = $managers->map(
            function ($manager) {
                return $manager->branchesServiced->pluck('id', 'branchname')->toArray();
            }
        );
        $branches = $branches->flatten()->toArray();
        sort($branches);
        return $branches;
    }
    /**
     
    /**
     * [_getCompaniesInCampaign Get assigned leads for company
     * currently owned by branches in the campaign
     * and unassigned within the campaign branches service area (box)]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return Collection Companies in campaign with locations within
     * branch service area of campaign
     */
    private function _getLocationsOfCompaniesInCampaign(Campaign $campaign)
    {
        $branches = $campaign->branches;
        $box = $this->branch->getBoundingBox($branches);
        $company_ids = $campaign->companies->pluck('id')->toArray();
        return $this->company
            ->whereIn('id', $company_ids)
            ->with(
                [
                'assigned'=>function ($q) use ($branches) {
                    $q->whereHas(
                        'assignedToBranch', function ($q1) use ($branches) {
                            $q1->whereIn('branch_id', $branches->pluck('id')->toArray());
                        }
                    )->with('assignedToBranch');
                },
                'unassigned'=>function ($q) use ($box) {
                    $q->where('lat', '<', $box['maxLat'])
                        ->where('lat', '>', $box['minLat'])
                        ->where('lng', '<', $box['maxLng'])
                        ->where('lng', '>', $box['minLng']);
                    
                }
                ]
            ) 
            ->get();
    }
    /**
     * [_getAllLocations description]
     * 
     * @param [type] $companies [description]
     * 
     * @return [type]            [description]
     */
    private function _getAllLocations($companies)
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
     * @param [type] $locations [description]
     * 
     * @return [type]            [description]
     */
    private function _getBranchesWithinServiceArea($campaign, $locations)
    {
        
  
        $branch_ids = $campaign->branches->pluck('id')->toarray();


       
        $box = $this->address->getBoundingBox($locations);
     
        return $this->branch->getWithinMBR($box)
            ->find($branch_ids);
        /*
        foreach ($branches as $branch) {
            $branchlocations = $branch
                ->`($locations, 25)
                ->get();
            dd($branch->id, $branchlocations);
            if ($branch->count()) {
                $assignments['location'][$location->id][] = $branch->first()->id;
                $assignments['branch'][$branch->first()->id][] = $location->id;
            
            } else {
                $assignments['unassigned'][] = $location->id;
            }

        }
        */
    }
    /**
     * [_getAssignedLeadsForBranches description]
     * 
     * @param  [type] $campaign [description]
     * @return [type]           [description]
     */
    private function _getAssignedLeadsForBranches($campaign, $assignedLocations) :Array
    {
        $branch = $assignedLocations->map(
            function ($address) {
                return $address->assignedToBranch->pluck('id')->toArray();
            }
        );
        $ids = $branch->unique()->flatten();
        
        foreach ($ids as $id) {
            
            $data[$id] = $assignedLocations->filter(
                function ($address) use ($id) {
                    return $address->assignedToBranch->where('id', $id)->count() > 0;
                }
            );
        }
        return $data;
        /*
        //$company_ids = $campaign->companies->pluck('id')->toArray();
        $lead_ids = $assignedLocations->pluck('id')->toArray();
        
        $serviceline_ids = $campaign->servicelines->pluck('id')->toArray();
        $branch_ids = $campaign->branches->pluck('id')->toarray();
        return $this->branch
            ->whereHas(
                'leads', function ($q) use ($lead_ids) {
                    $q->whereIn('addresses.id', $lead_ids);
                }
            )
            ->withCount(
                [
                    'leads'=>function ($q) use ($lead_ids) {
                        $q->whereIn('addresses.id', $lead_ids);
                    }, 
                    'staleLeads'=>function ($q) use ($lead_ids) {
                        $q->whereIn('addresses.id', $lead_ids);
                    }
                ]
            )
            ->whereHas(
                'servicelines', function ($q) use ($serviceline_ids) {
                    $q->whereIn('id', $serviceline_ids);
                }
            )
            ->find($branch_ids);
      */
    }
    /**
     * [_assignBranchLeads Loop through all assignable locations
     * and find the nearest branch from the ones in the campaign]
     * 
     * @param [type] $locations [description]
     * @param [type] $branches  [description]
     * 
     * @return [type]            [description]
     */
    private function _assignBranchLeads($locations,$campaign) 
    {
        
        $branch_ids = $campaign->branches->pluck('id')->toArray();
      
        $assignments = ['unassigned'=>[],'branch'=>[],'location'=>[]];
        
        $assignments = $locations->chunk(
            100, function ($location) use ($branch_ids) {
                $branch = $this->branch->whereIn('id', $branch_ids)
                    ->nearby($location, 25, 1)
                    ->get();
                dd($branch, $branch_ids);
                if ($branch->count()) {
                    $assignments['location'][$location->id][] = $branch->first()->id;
                    $assignments['branch'][$branch->first()->id][] = $location->id;
                
                } else {
                    $assignments['unassigned'][] = $location->id;
                }
                return $assignments;
            }
        );
        /* foreach ($locations as $location ) {
            $branch = $this->branch->whereIn('id', $branch_ids)
                ->nearby($location, 25, 1)
                ->get();
            if ($branch->count()) {
                $assignments['location'][$location->id][] = $branch->first()->id;
                $assignments['branch'][$branch->first()->id][] = $location->id;
            
            } else {
                $assignments['unassigned'][] = $location->id;
            }
            
         } */
        
        $assignments['branches'] = $this->branch->whereIn('id', array_keys($assignments['branch']))->get();
        
        return $assignments;
    }

}
