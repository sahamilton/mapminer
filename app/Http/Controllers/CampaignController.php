<?php

namespace App\Http\Controllers;
use App\Address;use App\Company;
use App\Branch;
use App\SearchFilter;
use App\Campaign;
use App\Serviceline;
use App\SalesOrg;
use App\Addresses;
use App\Person;

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
     * [__construct description]
     * 
     * @param Campaign $campaign [description]
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
        $companies = $this->company->whereIn('accounttypes_id', [1,4])->get();
        $servicelines = $this->serviceline->all();
        $roles = [6=>'svp',7=>'rvp', 3=>'market_manager'];
        $managers = $this->person->withRoles(array_keys($roles))->with('userdetails.roles')->get();
        
       
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
       
        $branches = $this->_getbranchesFromManager($data);
        
        $campaign = $this->campaign->create($data);
        
        $campaign->servicelines()->attach($data['serviceline']); 
        
        if (isset($data['vertical'])) {
            $campaign->vertical()->attach($data['vertical']);
           
        }
        $campaign->branches()->attach(array_keys($branches));
        $campaign->companies()->attach($data['companies']);
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
        $campaign->load('vertical', 'servicelines', 'branches', 'companies.managedBy', 'manager');
        $comps = $campaign->companies->map(
            function ($company) {
                return [$company->companyname=>$company->locations->count()];

            }
        );

        return response()->view('campaigns.show', compact('campaign', 'comps'));
    }
    public function edit(Campaign $campaign)
    {
        $verticals = $this->vertical->industrysegments();
        $companies = $this->company->whereIn('accounttypes_id', [1,4])->get();
        $servicelines = $this->serviceline->all();
        $roles = [6=>'svp',7=>'rvp', 3=>'market_manager'];
        $managers = $this->person->withRoles(array_keys($roles))->with('userdetails.roles')->get();
        $campaign->load('vertical', 'servicelines',  'manager'); 
        return response()->view('campaigns.edit', compact('campaign', 'verticals', 'companies', 'managers', 'servicelines'));
    }

    public function update(Campaign $campaign, Request $request) 
    {
        
        $data = $this->_transformRequest($request);
        
        $campaign->update($data);
        $branches = $this->_getbranchesFromManager($data);

        $campaign->servicelines()->sync($data['serviceline']); 
  
        if (isset($data['vertical'])) {
            $campaign->vertical()->sync($data['vertical']);
           
        }
        $campaign->branches()->sync(array_keys($branches));
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


    public function details(Campaign $campaign)
    {
        
        $campaign->load('branches', 'companies', 'servicelines');
        
        $locations = $this->_getLocations($campaign);
        
        $branches = $this->_getBranchesWithinServiceArea($campaign, $locations);
        
        $assignments = $this->_assignBranchLeads($locations, $branches);
        
    }

    private function _transformRequest(Request $request)
    {
        $data = request()->except(['_token']);
        $data['datefrom'] = Carbon::parse($data['datefrom']);
        $data['dateto'] = Carbon::parse($data['dateto']);
        $data['created_by'] = auth()->user()->id;
        return $data;
        
    }

    private function _getbranchesFromManager($data)
    {
       
        if (! $data['manager_id']) {
            return $this->branch->whereHas(
                'servicelines', function ($q) use ($data) {
                    $q->whereIn('id', $data['serviceline']);
                }
            )->pluck('branchname', 'id')->toArray();
        }

        $manager = $this->person->whereId($data['manager_id'])->firstOrFail();
        return $this->person->myBranches($manager);
    }
    private function _getLocations($campaign)
    {
        
        
        $locations = collect($this->address);
        foreach ($campaign->companies as $company) {
            $loc = $this->company
                ->where('id', $company->id)
                ->with(
                    [
                        'locations'=>function ($q) {
                            $q->doesntHave('assignedToBranch');
                        }
                    ]
                )->firstOrFail();

            $locations = $locations->merge($loc);
        }
        
        return $locations;
    }
    private function _getBranchesWithinServiceArea($campaign, $locations)
    {
        
        $branch_ids = $campaign->branches->pluck('id')->toarray();
     
        $box = $this->address->getBoundingBox($locations);
        return $this->branch->getWithinMBR($box)
            ->find($branch_ids);
        
    }

    private function _assignBranchLeads($locations, $branches) 
    {
       
        foreach ($locations as $location ) {
        
            $branches = $this->branch
                ->nearby($location, 25, 1)
                ->whereIn('id', $branches->pluck('id')->toarray())
                ->get();
            if ($branches->count()) {
                foreach ($branches as $branch)
                    $branch->locations()->attach($location);
            }
            
        }
    }
}
