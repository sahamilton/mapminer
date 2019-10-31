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

        return response()->view('campaigns.index', compact('campaigns'));
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
    public function store(Request $request)
    {
       
        $data = $this->_transformRequest($request);
      
        $branches = $this->_getbranchesFromManager($request, $data['manager_id']);
        
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

           
            $campaign->load('vertical', 'servicelines', 'branches', 'companies.managedBy', 'manager', 'team');
 
            $data = $this->_getCampaignData($campaign);
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
    public function update(Campaign $campaign, Request $request) 
    {
        
        $data = $this->_transformRequest($request);
        
        $campaign->update($data);
       
        $data['branches'] = $this->_getbranchesFromManager($request, $data['manager_id']);

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
        // get the branches in campaign that are already servicing the companies locations
        $data['branches'] =  $this->_getAssignedLeadsForBranches($campaign, $data['locations']['assigned']);
        // assign the unassigned locations  
        $data['assignments'] = $this->_assignBranchLeads($data['locations']['unassigned'], $campaign);
        
        // Merge the branches that could have locations with those that do
        
        $data['branches'] = $data['branches']->merge($data['assignments']['branches']);
        
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
        $data['datefrom'] = Carbon::parse($data['datefrom']);
        $data['dateto'] = Carbon::parse($data['dateto']);
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
    private function _getbranchesFromManager(Request $request, $manager_id)
    {
        
        $managers = $this->person->whereId([$manager_id])->firstOrFail()->descendantsAndSelf()
            ->with(
                ['branchesServiced'=>function ($q) use ($request) {
                    $q->whereHas(
                        'servicelines', function ($q1) use ($request) {
                            $q1->whereIn('id', request('serviceline'));
                        }
                    );
                }
                ]
            )->get();
        $branches = $managers->map(
            function ($manager) {
                return $manager->branchesServiced->pluck('id', 'brancname')->toArray();
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
                    );
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
        
    }
    /**
     * [_getAssignedLeadsForBranches description]
     * 
     * @param  [type] $campaign [description]
     * @return [type]           [description]
     */
    private function _getAssignedLeadsForBranches($campaign, $assignedLocations)
    {
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

}
