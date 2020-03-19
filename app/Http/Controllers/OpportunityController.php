<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityType;
use App\Address;
use App\AddressBranch;
use App\Branch;
use App\Company;
use App\Contact;
use App\Note;
use App\Http\Requests\OpportunityFormRequest;
use App\Opportunity;
use App\Person;
use App\SalesOrg;
use \Carbon\Carbon;

use Illuminate\Http\Request;

class OpportunityController extends BaseController
{
    
    public $address;
    public $addressbranch;
    public $branch;
    public $contact;
    public $opportunity;
    public $activity;
    public $person;
    public $period;

    /**
     * [__construct description]
     * 
     * @param Activity      $activity      [description]
     * @param Address       $address       [description]
     * @param AddressBranch $addressbranch [description]
     * @param Branch        $branch        [description]
     * @param Contact       $contact       [description]
     * @param Opportunity   $opportunity   [description]
     * @param Person        $person        [description]
     */
    public function __construct(
        Activity $activity,
        Address $address,
        AddressBranch $addressbranch,
        Branch $branch,
        Contact $contact,
        Opportunity $opportunity,
        Person $person,
        SalesOrg $salesorg
    ) {
        $this->activity = $activity;
        $this->address = $address;
        $this->addressbranch = $addressbranch;
        $this->branch = $branch;
        $this->contact = $contact;
        $this->opportunity = $opportunity;
        $this->person = $person;
        $this->salesorg = $salesorg;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $person = auth()->user()->person;
     
        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }

        $activityTypes = $activityTypes = ActivityType::all();
        $myBranches = $this->person->myBranches();
       
        if (! $myBranches) {
            return redirect()->back()
                ->withWarning("You are not assigned to any branches. Please contact Sales Operations");
        }
        session(['branch'=>array_keys($myBranches)[0]]);
        $data['period'] = $this->period;

        if (count($myBranches) == 1 ) {
            
            $data = $this->_getBranchData([session('branch')]);
            return response()->view(
                'opportunities.index', 
                compact('data', 'activityTypes', 'myBranches', 'period')
            );

        } else {
            $person = $this->_getManagers();
            $managers = $person->load('directReports')->directReports;
          
            $data['summary'] = $this->_getBranchSummaryData(array_keys($myBranches));
           
            return response()->view(
                'opportunities.summary', 
                compact('data', 'activityTypes', 'myBranches', 'managers', 'person')
            );
        }
             
        
    }
    /**
     * [showBranchOpportunities description]
     * 
     * @param  Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function showBranchOpportunities(Branch $branch)
    {
        $myBranches = $this->person->myBranches();
        if ($branch->id) {
         
            if (! array_key_exists($branch->id, $myBranches)) {
                 return redirect()->back()
                     ->withWarning("You are not assigned to " .$branch->branchname);
            }
        }
        session(['branch'=>$branch->id]);
        $data = $this->_getBranchData([session('branch')]);
        return response()->view(
            'opportunities.index', 
            compact('data', 'activityTypes', 'myBranches', 'period')
        );

    }
    /**
     * [branchOpportunities description]
     * 
     * @param Branch  $branch  [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function branchOpportunities(Branch $branch, Request $request)
    {
        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }
        // check that user is assigned to branch
        $myBranches = $this->person->myBranches();
        if ($branch->id) {
         
            if (! array_key_exists($branch->id, $myBranches)) {
                 return redirect()->back()
                     ->withWarning("You are not assigned to " .$branch->branchname);
            }
        }
        if (request()->has('branch')) {
           
            $data = $this->_getBranchData([request('branch')]);
        } else {

            $data = $this->_getBranchData([$branch->id]);
        }

        $activityTypes = $activityTypes = ActivityType::all();
       
        $data['period'] = $this->period;
        
        return response()->view(
            'opportunities.index', 
            compact('data', 'activityTypes', 'myBranches')
        );
    }
    /**
     * [branchOpportunities description]
     * 
     * @param Branch  $branch  [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function managerOpportunities(Request $request)
    {
        $person = $this->person->findOrFail(request('manager'));
        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }
        $data['period']= $this->period;
        // need to get my team
        // auth()->user()->person->myteam();
        // check that user is assigned to branch
        $myBranches = $this->person->myBranches($person);

        if (count($myBranches) == 1 ) {
            
            $data = $this->_getBranchData([session('branch')]);
            return response()->view(
                'opportunities.index', 
                compact('data', 'activityTypes', 'myBranches', 'period')
            );

        } else {
            
            $data['summary'] = $this->_getBranchSummaryData(array_keys($myBranches), $this->period);
           
            $managers = $person->load('directReports')->directReports;
            return response()->view(
                'opportunities.summary', 
                compact('data', 'activityTypes', 'myBranches', 'person', 'managers')
            );
        }
    }
    /**
     * [_getBranchSummaryData description]
     * 
     * @param array  $branches [description]
     * @param [type] $period   [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranchSummaryData(array $branches)
    {
        return $this->branch->summaryBranchOpportunities($this->period)
            ->whereIn('id', $branches)
            ->get();
    } 
    /**
     * [_getBranchData description]
     *  
     * @param array  $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranchData(array $branches)
    {
        $data['branches'] =$this->_getBranches($branches);

        $data['opportunities'] = $this->_getOpportunities($branches);

        

        
        return $data;
    }
    /**
     * [findLocations description]
     * 
     * @param [type] $distance [description]
     * @param [type] $latlng   [description]
     * 
     * @return [type]           [description]
     */
    public function findOpportunities($distance = null, $latlng = null)
    {
       
        $location = $this->getLocationLatLng($latlng);
      
        $result = $this->address->whereHas(
            
            'opportunities', function ($q) use ($location, $distance) {
                $q->where('closed', 0);
            }
        )->nearby($location, $distance)
        ->with('opportunities')->get();
      
        return response()->view('opportunities.xml', compact('result'))->header('Content-Type', 'text/xml');
    }
    /**
     * [getBranches description]
     * 
     * @param [type] $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranches($branches)
    {
        return  $this->branch->with('opportunities', 'leads', 'manager')
            ->whereIn('id', $branches)
            ->get();
    }
    /**
     * [_getOpportunities description]
     * 
     * @param [type] $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getOpportunities($branches, $location = null)
    {
        $opportunities = $this->opportunity
            ->whereIn('branch_id', $branches)
            ->with(
                ['address.address'=>function ($query) {
                    $query->withLastActivityId();
                },'address.address.lastActivity']
            )
            ->thisPeriod($this->period)
            ->orderBy('branch_id')
            ->distinct();
           
        if ($location) {
            $opportunities = $opportunities->nearby($location);
        }
        return $opportunities->get();
       
    }
      
    private function _getManagers()
    {
        if (auth()->user()->hasRole(['admin'])) {
            $manager = $this->salesorg->getCapoDiCapo();
        } else {
            $manager = $this->person->where('user_id', '=', auth()->user()->id)->firstOrFail();
        }
        return $manager;
    }  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(OpportunityFormRequest $request)
    {
        
        //need to remove it from all the other branches when an oppty is created
        $address = $this->address->findOrFail(request('address_id'));
        $address->assignedToBranch()->sync([request('branch_id')]);
        // make sure that the relationship exists
        $join = $this->addressbranch
            ->where('address_id', '=', request('address_id'))
            ->where('branch_id', '=', request('branch_id'))
            ->firstOrCreate(
                ['address_id'=>request('address_id'), 
                'branch_id'=>request('branch_id')]
            );
        
        $data = request()->except('_token');
        if (! request()->filled('csp')) {
            $data['csp'] = 0;
        }
        if (request()->filled('expected_close')) {
            $data['expected_close'] = Carbon::parse($data['expected_close']);
        }
        if (in_array(request('closed'), ['1','2'])) {
            $data['actual_close'] = request('expected_close');
        }
        if (isset($data['actual_close'])) {
            $data['actual_close'] = Carbon::parse($data['actual_close']);
        }

        $data['user_id'] = auth()->user()->id;

        $join->opportunities()->create($data);

        return redirect()->back()->withMessage("Added to branch opportunities");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Opportunity $opportunity [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Opportunity $opportunity)
    {
        $opportunity->load('branch', 'address');
        
      
        return response()->view('opportunities.show', compact('opportunity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Opportunity $opportunity [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Opportunity $opportunity)
    {
       
        return response()->view('opportunities.edit', compact('opportunity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request     [description]
     * @param \App\Opportunity         $opportunity [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(OpportunityFormRequest $request, Opportunity $opportunity)
    {
        
        $data = request()->except(['_token','_method','submit']);
        $data['user_id'] = auth()->user()->id;
        if ($data['expected_close']) {
            $data['expected_close'] = Carbon::parse($data['expected_close']);
        }
        if ($data['actual_close']) {
            $data['actual_close'] = Carbon::parse($data['actual_close']);
        }
        $opportunity->update($data);
        
        return redirect()->route(
            'address.show', $opportunity->address_id
        )
            ->withMessage('Opportunity updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Opportunity $opportunity [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Opportunity $opportunity)
    {
        
        $address = $opportunity->address->address_id;
        $opportunity->delete();
        return redirect()->route('address.show', $address)
            ->withMessage('Opportunity deleted');
    }
    /**
     * [remove description] 
     * 
     * @param Address $address [description]
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function remove(Address $address, Request $request)
    {
        
        $address->assignedToBranch()->detach(request('branch_id'));
       
        return redirect()->route('branch.leads', request('branch_id'))
            ->withMessage('Lead removed');
    }
    /**
     * [addToBranchLeads description]
     * 
     * @param Address $address [description]
     * @param Request $request [description]
     *
     * @return redirect [<description>]
     */
    public function addToBranchLeads(Address $address, Request $request)
    {
 
    
        $test = $this->addressbranch->where('address_id', '=', $address->id)
            ->where('branch_id', '=', request('branch_id'))->get();
        if ($test->count()>0) {
            return redirect()->back()
                ->withError($address->businessname .' is already on the branch '. request('branch_id'). ' leads list');
        }

        $address->assignedToBranch()->attach(request('branch_id'));
        return redirect()->back()->withMessage('Added to Branch Leads');
    }
    /**
     * [close description]
     * 
     * @param Request $request     [description]
     * @param [type]  $opportunity [description]
     * 
     * @return [type]               [description]
     */
    public function close(OpportunityFormRequest $request, $opportunity)
    {

        $data= request()->except('_token');
        $branch = $opportunity->branch_id;
        if (request()->filled('actual_close')) {
            $data['actual_close'] = Carbon::now();
        }
        
        $opportunity->update($data);
        $opportunity->load('address', 'address.address', 'address.address.company');
            // check to see if the client_id exists else create new company
        if (request()->filled('client_id')) {
            $company = Company::where('client_id', '=', request('client_id'))
                ->firstOrCreate(
                    [
                    'companyname'=>$address->address->businessname,
                    'accounttypes_id'=>3,
                    'customer_id'=>request('client_id')
                    ]
                );
            $address->update(['company_id' => $company->id]);
        }
        return redirect()->route('opportunities.branch', $branch)->withMessage('Opportunity closed');

    }
    /**
     * [toggle description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function toggle(Request $request)
    {
        
        $opportunity = $this->opportunity->findOrFail(request('id'));
 
        if ($opportunity->Top25 == 1) {
            $opportunity->Top25 = null;
        
        } else {
            $opportunity->Top25 = 1;
          
        }
        if ($opportunity->save()) {
            return response()->json(
                ['message'=>'success'], 200
            );
        }      
        
    }
}
