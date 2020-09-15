<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Serviceline;
use App\Location;
use App\User;
use App\Activity;
use App\Address;
use App\AddressBranch;
use App\State;
use App\Opportunity;
use App\Person;
use App\Role;
use Excel;
use App\Http\Requests\BranchFormRequest;
use App\Http\Requests\BranchReassignFormRequest;
use App\Http\Requests\BranchImportFormRequest;
use App\Exports\BranchTeamExport;

class BranchesController extends BaseController
{

    /**
     * Display a listing of branches
     *
     * @return Response
     */
    public $activity;
    public $addressBranch;
    public $branch;
    public $serviceline;
    public $opportunity;
    public $person;
    public $state;

    
    /**
     * [__construct description]
     * 
     * @param Branch      $branch      [description]
     * @param Serviceline $serviceline [description]
     * @param Person      $person      [description]
     * @param State       $state       [description]
     * @param Address     $address     [description]
     */
    public function __construct(
        Branch $branch, 
        Serviceline $serviceline,
        Opportunity $opportunity,
        Person $person, 
        State $state, 
        Address $address,
        AddressBranch $addressBranch,
        Activity $activity
    ) {
        $this->activity = $activity;
        $this->address = $address;
        $this->addressBranch = $addressBranch;
        $this->branch = $branch;
        $this->opportunity = $opportunity;
        $this->person = $person;
        $this->serviceline = $serviceline;
        $this->state = $state;
       
        parent::__construct($this->branch);

            
    }
    
    /**
     * List all branches with region, manager filtered by users serviceline
     * 
     * @return [type] [description]
     */
    public function index()
    {
       
        return response()->view('branches.index');
    }

    public function newleads()
    {
        $myBranches = ['1201', '1204'];
        return response()->view('branches.test', compact('myBranches'));
    }
    
    /**
     * Display map from stored xml file
     * 
     * @return [type] [description]
     */
    public function mapall()
    {
        
        $servicelines = $this->serviceline->all();
        $allstates = $this->branch->allstates();
    
        return response()->view('branches.map', compact('servicelines', 'allstates'));
    }
    
    /**
     * [getAllbranchmap description]
     * 
     * @return [type] [description]
     */
    public function getAllbranchmap()
    {
        $branches = $this->branch->with('servicelines')->get();
    
        $content = view('branches.xml', compact('branches'));
        return response($content, 200)
            ->header('Content-Type', 'text/xml');    
        

    }
    
    /**
     * Show the form for creating a new branch
     *
     * @return Response
     */
    public function create()
    {

        $branchRoles = Role::whereIn('id', $this->branch->branchRoles)
        ->pluck('display_name', 'id');
        $team = $this->person->personroles($this->branch->branchRoles);
        $servicelines = $this->serviceline
            ->whereIn('id', $this->userServiceLines)->get();
        return response()->view(
            'branches.create', 
            compact('servicelines', 'team', 'branchRoles')
        );

    }

    /**
     * [store description]
     * 
     * @param BranchFormRequest $request [description]
     * 
     * @return [type]                     [description]
     */
    public function store(BranchFormRequest $request)
    {
        $address = request('street')." ".request('address2').' '.request('city').' ' .request('state').' ' .request('zip');

        $geoCode = app('geocoder')->geocode($address)->get();
        $geodata = $this->branch->getGeoCode($geoCode);
        $input = array_merge(request()->all(), $geodata);
    
        // add lat lng to location
        $branch = $this->branch->create($input);

        $branch->associatePeople($request);
        $branch->servicelines()->sync($input['serviceline']);
        //$this->_rebuildXMLfile();

        return redirect()->route('branches.show', $branch->id);
    }
    /**
     * [rebuildBranchMap description]
     * 
     * @return [type] [description]
     */
    public function rebuildBranchMap()
    {
        $this->_rebuildXMLfile();
        return redirect()->route('branch.map');

    }
    /**
     * [_rebuildXMLfile description]
     * 
     * @return [type] [description]
     */
    private function _rebuildXMLfile()
    {
        
        $branches = $this->branch->with('servicelines')->get();
        $xml = response()->view('branches.xml', compact('branches'))
            ->header('Content-Type', 'text/xml');
        $file = file_put_contents(
            storage_path() . '/app/public/uploads/branches.xml', $xml
        );
        return true;
    }

    public function quickadd(Request $request)
    {
        $destinations = [
            'lead'=>'myleads.create',
            'activity'=>'myleadsactivity.create',

            ];
        return redirect()->route($destinations[request('type')]);
    }
    /**
     * [show description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function show(Branch $branch)
    {

        $servicelines = $this->serviceline
            ->whereIn('id', $this->userServiceLines)->get();
        // need a try here 
        // check to see that this branch can be seen by this user
        // move to model
        $data['branch'] = $this->branch
            ->whereHas(
                'servicelines', function ($q) {
                    $q->whereIn('serviceline_id', $this->userServiceLines);
 
                }
            )
            ->findOrFail($branch->id);

        $filtered = $this->branch->isFiltered(['companies'], ['vertical']);
        
        // in case of null results of manager search
    
        $data['fulladdress'] = $branch->fullAddress();
        $data['urllocation'] ="api/mylocalaccounts";
        $data['title'] ='National Account Locations';
        $data['company']=null;
        //$data['companyname']=NULL;
        $data['latlng'] = $data['branch']->lat.":".$data['branch']->lng;
        $data['distance'] = '10';


        $roles = Role::pluck('display_name', 'id');

        return response()->view(
            'branches.show', 
            compact('data', 'servicelines', 'roles')
        );
    }
    /**
     * [showSalesTeam description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function showSalesTeam($id)
    {
        $salesteam = $this->branch->with('relatedPeople', 'servicelines')->find($id);

        $roles = Role::pluck('display_name', 'id');
    

        return response()->view('branches.showteam', compact('salesteam', 'roles'));
    }
    
    /**
     * [showNearbyBranches description]
     * 
     * @param Request $request [description]
     * @param [type]  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function showNearbyBranches(Request $request, Branch $branch)
    {
        

        if (request()->filled('d')) {
            $data['distance'] = request('d');

        } else {
            $data['distance'] = '50';
        }
        $data['branch'] = $branch;
        //$data['branches'] = $this->branch->nearby($branch,25,5)->get();

        return response()->view('branches.nearby', compact('data'));
    }
    /**
     * [map description]
     * 
     * @param Request $request [description]
     * @param [type]  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function map(Request $request, Branch $branch)
    {
        
        $locations = Location::nearby($branch, 25)->get();

        return response()->json(['error'=>false,'locations' =>$locations->toArray()], 200)
            ->setCallback(request('callback'));

        
    }

    /**
     * [edit description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function edit(Branch $branch)
    {
        

        $branchRoles = \App\Role::whereIn('id', $this->branch->branchRoles)
            ->pluck('display_name', 'id');

        $team = $this->person->personroles($this->branch->branchRoles);
        //$branch = $this->branch->find($branch->id);    
        $branchteam = $branch->relatedPeople()->pluck('persons.id')->toArray();
        $servicelines = $this->serviceline->whereIn(
            'id', $this->userServiceLines 
        )->get();
        $branchservicelines = $branch->servicelines()
            ->pluck('servicelines.id')->toArray();


        return response()->view(
            'branches.edit', 
            compact('branch', 'servicelines', 'branchRoles', 'team', 'branchteam', 'branchservicelines')
        );

    }

    /**
     * [update description]
     * 
     * @param BranchFormRequest $request [description]
     * @param [type]            $branch  [description]
     * 
     * @return [type]                     [description]
     */
    public function update(BranchFormRequest $request, Branch $branch)
    {
        
        $data = request()->all();
        $address = $data['street'] . " ". $data['city'] . " ". $data['state'] . " ". $data['zip'];    
        
        $geoCode = app('geocoder')->geocode($address)->get();

        $latlng = ($this->branch->getGeoCode($geoCode));
        $data['lat']= $latlng['lat'];
        $data['lng']= $latlng['lng'];      
        
        $branch->update($data);
        $branch->associatePeople($request);
        
        $branch->servicelines()->sync(request('serviceline'));
        //$this->_rebuildXMLfile();
        return redirect()->route('branches.show', $branch->id);

        
    }
    /**
     * [destroy description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function destroy(Branch $branch)
    {
        $branch->load('openActivities', 'openOpportunities', 'allLeads');
        if ($branch->openActivities->count() > 0
            or $branch->openOpportunities->count() > 0
            or $branch->allLeads()->count() > 0
        ) {
            return redirect()->route('branchReassign', $branch->id)->withError('You must first reassign the leads, open opportunities and open activities');
        } else {
            
            $branch->delete();
            //$this->_rebuildXMLfile();
            return redirect()->route('branches.index')->withSuccess($branch->branchname . ' has been deleted');
        }
        
    }
    /**
     * [reassignBranch description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function reassignBranch(Branch $branch)
    {
        $branch->load('openActivities', 'openOpportunities', 'allLeads');
        $branches = $this->branch->orderBy('id')->get();
        $nearby = $this->branch->nearby($branch, 100, 5)->get();
        return response()->view('branches.reassign', compact('branch', 'branches', 'nearby'));
    }
    /**
     * [reassign description]
     * 
     * @param BranchReassignFormRequest $request [description]
     * @param Branch                    $branch  [description]
     * 
     * @return [type]                            [description]
     */
    public function reassign(BranchReassignFormRequest $request, Branch $branch)
    {
        if (request()->filled('nearbranch')) {
            $newbranch = request('nearbranch');
        } else {
            $newbranch = request('newbranch');
        }
        $leads = $this->addressBranch
            ->where('branch_id', $branch->id)
            ->update(['branch_id'=> $newbranch]);
              
        $opportunities = $this->opportunity
            ->where('closed', 0)
            ->where('branch_id', $branch->id)
            ->update(['branch_id'=> $newbranch]);
      
        $activities = $this->activity
            ->whereNull('completed')
            ->where('branch_id', $branch->id)
            ->update(['branch_id'=> $newbranch]);

        if (request()->filled('delete')) {
            $branch->delete();
        }

        return redirect()->route('branches.show', $newbranch)->withSuccess('All leads & opportunities & open activities have been reassigned from ' . $branch->branchname . ' to branch ' . $newbranch);
    }
    /**
     * [listNearbyLocations description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function listNearbyLocations(Branch $branch)
    {        
        $branch->load('manager.reportsTo','manager.userdetails');
        return response()->view(
            'branches.showlist', 
            compact('branch')
        );

    }
    /**
     * [getNearbyBranches description]
     * 
     * @param Request $request [description]
     * @param [type]  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function getNearbyBranches(Request $request, Branch $branch)
    {
        
        if (request()->filled('d')) {
            $distance = request('d');

        } else {
            $distance = '50';
        }
        

        $servicelines = $this->userServiceLines;
    
        $branches = $this->branch->whereHas(
            'servicelines', function ($q) use ($servicelines) {
                $q->whereIn('id', $servicelines);
            }
        )
        ->nearby($branch, $distance)
        ->get();
        
        return response()->view(
            'branches.xml', compact('branches')
        )->header('Content-Type', 'text/xml');

        
    }

    /**
     * [branchLeads description]
     * 
     * @param Request $request [description]
     * @param [type]  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function branchLeads(Request $request, Branch $branch=null)
    {
        $myBranches = $this->person->myBranches();
      
        if (! $branch = $this->branch->checkIfMyBranch($request, $myBranches, $branch)) {
            return redirect()->back()
                ->withWarning('You are not associated with these branches');
        }
    
        $leads = \App\AddressBranch::whereDoesnthave('opportunities')
            ->with('branch', 'address', 'address.leadsource')
            ->whereIn('branch_id', [$branch->id])
            ->get();
        

        return response()->view('branches.leads', compact('leads', 'myBranches'));
    }

    /**
     * [branchOpportunities description]
     * 
     * @param Request $request [description]
     * @param [type]  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function branchOpportunities(Request $request, Branch $branch=null)
    {
        
        $myBranches = $this->person->myBranches();
        if (! $branch = $this->branch->checkIfMyBranch($request, $branch, $myBranches)) {
            return redirect()->back()
                ->withWarning('You are not associated with these branches');
        }
        

        $branch->load('opportunities', 'opportunities.address');

        return response()->view(
            'branches.opportunities', 
            compact('branch', 'myBranches')
        );
    }
    /**
     * [statemap description]
     * 
     * @param Request $request [description]
     * @param [type]  $state   [description]
     * 
     * @return [type]           [description]
     */
    public function statemap(Request $request, $state=null)
    {
        
        $servicelines = $this->serviceline->whereIn('id', $this->userServiceLines)
            ->get();

        if (! isset($state)) {

            $state=request('state');

        }
        $allstates = $this->branch->allStates();
        $data = $this->state->where('statecode', '=', $state)
            ->firstOrFail()->toArray();
        $data['type'] = 'branch';
        return response()->view(
            'branches.statemap', 
            compact('data', 'servicelines', 'allstates')
        );    
        
    }
    /**
     * [makeStateMap description]
     * 
     * @param [type] $state [description]
     * 
     * @return [type]        [description]
     */
    public function makeStateMap($state)
    {
        $branches = $this->branch->with('servicelines')
            ->where('state', '=', $state)->get();
        
        return response()->view('branches.xml', compact('branches'));
    }
    /**
     * [retrieveStateBranches description]
     * 
     * @param [type] $state [description]
     * 
     * @return [type]        [description]
     */
    public function retrieveStateBranches($state)
    {
        
        return $this->branch->whereHas(
            'address', function ($q) use ($state) {
                $q->where('state', '=', $state);
            }
        )
        ->with('address', 'servicelines', 'servicedBy')
        ->whereHas(
            'servicelines', function ($q) {
                $q->whereIn('serviceline_id', $this->userServiceLines);
            }
        )
        
        ->get()->sortBy('city');

        
    }
    /**
     * [mapMyBranches description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function mapMyBranches($id)
    {    
        $people = $this->person->with('manages')->findOrFail($id);
        $branches = $people->manages;
        return response()->view('branches.xml', compact('branches'))
            ->header('Content-Type', 'text/xml');
    }
    /**
     * [getMyBranches description]
     * 
     * @param [type] $id [description]
     * 
     * @return [type]     [description]
     */
    public function getMyBranches($id)
    {    
        
        $data['people'] = $this->person->with('manages', 'userdetails')
            ->findOrFail($id);
    
        return response()->view('persons.showmap', compact('data', 'centerpos'));
    }
    
    
    /**
     * [state description]
     * 
     * @param Request $request   [description]
     * @param [type]  $statecode [description]
     * 
     * @return [type]             [description]
     */
    public function state(Request $request, $statecode=null)
    {
        
        $state = request('state');

        if (! $statecode) {

            $statecode = $state;

        }
    
        $branches = $this->branch
            ->with('region', 'servicelines', 'manager', 'relatedPeople', 'servicedBy')
    
            ->whereHas(
                'servicelines', function ($q) {
                        $q->whereIn('serviceline_id', $this->userServiceLines);

                }
            )
            ->where('state', '=', $statecode)
            ->orderBy('id')
            ->get();

        $state = \App\State::where('statecode', '=', $statecode)->first();
        $allstates = $this->branch->allStates();
        return response()->view(
            'branches.state', 
            compact('branches', 'state', 'allstates')
        );
        
    }
    /**
     * [exportTeam description]
     * 
     * @return [type] [description]
     */
    public function exportTeam() 
    {
    
        return Excel::download(new BranchExport(), 'BranchTeam.csv');
    }
    
     
    /**
     * [export description]
     * 
     * @return [type] [description]
     */
    public function export() 
    {
    
        return Excel::download(new BranchTeamExport(), 'Branch.csv');
    }
    /**
     * [geoCodeBranches description]
     * 
     * @return [type] [description]
     */
    public function geoCodeBranches()
    {

        $branches = $this->branch->where('lat', '=', 0)->take(100)->get();
        foreach ($branches as $branch) {
            $address = $this->branch->fullAddress($branch);
            $geocode = app('geocoder')->geocode($address)->get();
            $data = $this->branch->getGeoCode($geocode);
            
            
            $branch->lat = $data['lat'];
            $branch->lng = $data['lng'];
            $branch->update();

        }
        $this->_rebuildXMLfile();
    }
}