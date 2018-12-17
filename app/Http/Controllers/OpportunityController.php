<?php

namespace App\Http\Controllers;

use App\Opportunity;
use App\Person;
use App\Activity;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    
    public $person;
    public $opportunity;
    public $activity;

    public function __construct(Opportunity $opportunity, Person $person, Activity $activity){
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
        
       $activityTypes = $this->activity->activityTypes;
       $opportunities = $this->opportunity
        ->whereIn('branch_id',$this->person->myBranches())
        ->with('address','branch','activities')
        ->orderBy('branch_id')
        ->get();

        // is this a manager ?
        if($this->person->myTeam()->count() >1){
            return response()->view('opportunities.mgrindex',compact($opportunities));
        }
        return response()->view('opportunities.index',compact('opportunities','activityTypes'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function show(Opportunity $opportunity)
    {
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
