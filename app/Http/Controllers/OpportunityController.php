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
use \Carbon\Carbon;

use Illuminate\Http\Request;

class OpportunityController extends Controller
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
        Person $person
    ) {
        $this->address = $address;
        $this->addressbranch = $addressbranch;
        $this->branch = $branch;
        $this->contact = $contact;
        $this->opportunity = $opportunity;
        $this->person = $person;
        $this->activity = $activity;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }
        $activityTypes = $activityTypes = ActivityType::all();
        $myBranches = $this->person->myBranches();
        
        if (! $myBranches) {
            return redirect()->back()
                ->withWarning("You are not assigned to any branches. Please contact Sales Operations");
        }
        if (session()->has('branch')) {
            $data = $this->getBranchData([session('branch')]);
        } else {
            $data = $this->getBranchData([array_keys($myBranches)][0]);
        }
        

        $data['period'] = $this->period;
      
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
       
        $myBranches = $this->person->myBranches();

        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }
        // check that user is assigned to branch
        if ($branch->id) {
         
            if (! array_key_exists($branch->id, $myBranches)) {
                 return redirect()->back()
                     ->withWarning("You are not assigned to " .$branch->branchname);
            }
        }
        if (request()->has('branch')) {

            $data = $this->getBranchData([request('branch')]);
        } else {
             $data = $this->getBranchData([$branch->id]);
        }

        $activityTypes = $activityTypes = ActivityType::all();
       
        $data['period'] = $this->period;
        return response()->view(
            'opportunities.index', 
            compact('data', 'activityTypes', 'myBranches')
        );
    }
   
    /**
     * [getBranchData description]
     * 
     * @param array $branches [description]
     * 
     * @return [type]           [description]
     */
    public function getBranchData(array $branches)
    {
        $data['branches'] =$this->_getBranches($branches);


        $data['opportunities'] = $this->_getOpportunities($branches);

       

        $data['addresses'] = $data['opportunities']->map(
            function ($opportunity) {
                return $opportunity->address;
            }
        );

        $data['activities'] = $data['addresses']->map(
            function ($address) {
                if ($address) {
                    return $address->activities;
                }
            }
        );
       
       
        return $data;
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
    private function _getOpportunities($branches)
    {

        return $this->opportunity
            ->whereIn('branch_id', $branches)
            ->with('address', 'branch', 'address.activities')
            ->thisPeriod($this->period)
            ->orderBy('branch_id')
            ->distinct()
            ->get();
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