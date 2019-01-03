<?php

namespace App\Http\Controllers;

use App\Opportunity;
use App\Person;
use App\Branch;
use App\Address;
use App\Activity;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    
    public $person;
    public $opportunity;
    public $activity;
    public $branch;
    public $address;

    public function __construct(Opportunity $opportunity, Branch $branch, Person $person, Address $address,Activity $activity){
        $this->opportunity = $opportunity;
        $this->person = $person;
        $this->activity = $activity;
        $this->branch = $branch;
        $this->address = $address;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
       if($this->person->myTeam()->count() >1){
            $branches = $this->branch->with('opportunities','manager')
            ->whereIn('id',$this->person->myBranches())
            ->get();
            
            return response()->view('opportunities.mgrindex',compact('branches'));
        } else{
       $activityTypes = $this->activity->activityTypes;
       
       $opportunities = $this->opportunity
        ->whereIn('branch_id',array_keys($this->person->myBranches()))
        ->with('address','branch','address.activities')
        ->orderBy('branch_id')
        ->get();
        // is this a manager ?

        return response()->view('opportunities.index',compact('opportunities','activityTypes'));
        
        }
        // if no branches abort
        // if no branches then select branc / Sales OPs
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->opportunity->create(request()->all());
        return redirect()->route('address.show',request('address_id'))->withMessage("Added to branch opportunities");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function show(Opportunity $opportunity)
    {
        $opportunity->load('address');
        $address = $opportunity->address;

        $location = $address->load($address->addressable_type,'contacts','company','industryVertical',$address->addressable_type . '.relatedNotes');
        $branches = $this->branch->nearby($location,100,5)->get();
        $rankingstatuses = $this->address->getStatusOptions;
        $people = $this->person->salesReps()->PrimaryRole()->nearby($location,100,5)->get();
        
        return response()->view($location->addressable_type.'.show',compact('location','branches','rankingstatuses','people'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function edit(Opportunity $opportunity)
    {
        $opportunity = $opportunity->load('address','branch','activities','address.contacts');
        return response()->view('opportunities.show',compact('opportunity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Opportunity $opportunity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Opportunity $opportunity)
    {
        //
    }
}
