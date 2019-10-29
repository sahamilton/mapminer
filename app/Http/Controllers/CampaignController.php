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
        $managers = $this->person->withRoles(array_keys($roles))->with('userdetails.roles')->get();
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
        $branches = $this->_getbranchesFromManager($data);
       
        $campaign->servicelines()->sync($data['serviceline']); 
  
        if (isset($data['vertical'])) {
            $campaign->vertical()->sync($data['vertical']);
           
        }
        //$team = $this->campaign->setCampaignTeam();
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
        // 
        // add team
        // 
        // notify team
        // 
        // 
        
    }
    private function _getCampaignData(Campaign $campaign)
    {
        
        $branches = $campaign->branches;

        $locations = $this->_getCompanyLocations($campaign, $branches);

        $data['branches'] = $this->_getBranchesWithinServiceArea($campaign, $locations);
      
        $data['assignments'] = $this->_assignBranchLeads($locations, $branches);
        $data['locations'] = $locations;
        return $data;
    }
    /**
     * [_transformRequest description]
     * 
     * @param Request $request [description]
     * @return [type]           [description]
     */
    private function _transformRequest(Request $request)
    {
       
        $data = request()->except(['_token']);
        $data['datefrom'] = Carbon::parse($data['datefrom']);
        $data['dateto'] = Carbon::parse($data['dateto']);
        if (request()->has('vertical')) {
            $data['companies'] = $this->_getCompaniesInVertical($request);
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
    private function _getbranchesFromManager($data)
    {
        
        $manager = $this->person->whereId($data['manager_id'])->firstOrFail();
        return $this->person->myBranches($manager);
    }
    /**
     * [_getCompanyLocations get all locations of companies within branch territory (box)]
     * 
     * @param [type] $campaign [description]
     * @param [type] $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getCompanyLocations(Campaign $campaign, $branches)
    {
        
        $box = $this->branch->getBoundingBox($branches);

        $company_ids = $campaign->companies->pluck('id')->toArray();
        // get locations that are not already assigned to a branch
        $companies = $this->company
            ->whereIn('id', $company_ids)
            ->with(
                [
                    'locations'=>function ($q) {
                        $q->doesntHave('assignedToBranch');
                            ;
                    }
                ]
            )->get();
        // get the locations of the companies that are within the MBR
        $locations = $companies->map(
            function ($company) use ($box) {
                return $company->locations
                    ->where('lat', '<', $box['maxLat'])
                    ->where('lat', '>', $box['minLat'])
                    ->where('lng', '<', $box['maxLng'])
                    ->where('lng', '>', $box['minLng']);

            }
        );
        //dd($locations->flatten());
        return $locations->flatten();
        
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
        $company_ids = $campaign->companies->pluck('id')->toArray();
        $box = $this->address->getBoundingBox($locations);
        return $this->branch->getWithinMBR($box)->withCount(
            ['leads'=>function ($q) use ($company_ids) {
                $q->whereIn('company_id', $company_ids);
            }
            ]
        )->find($branch_ids);
        
    }
    /**
     * [_assignBranchLeads description]
     * 
     * @param [type] $locations [description]
     * @param [type] $branches  [description]
     * 
     * @return [type]            [description]
     */
    private function _assignBranchLeads($locations, $branches) 
    {
        //dd($locations, $branches);
        $branch_ids = $branches->pluck('id')->toArray();
        //$address_ids = $locations->pluck('id')->toArray();
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

        
        return $assignments;
    }

}
