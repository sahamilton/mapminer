<?php

namespace App\Http\Controllers;

use App\Opportunity;
use App\Lead;
use App\Person;
use App\Branch;
use App\Orders;
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
    public $orders;
    public $leads;

    public function __construct(Opportunity $opportunity, Orders $orders, Branch $branch, Person $person, Address $address,Activity $activity,Lead $leads){
        $this->opportunity = $opportunity;
        $this->person = $person;
        $this->activity = $activity;
        $this->branch = $branch;
        $this->address = $address;
        $this->orders = $orders;
        $this->leads = $leads;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if(! auth()->user()->hasRole('Branch Manager') && $this->person->myTeam()->count() >1){
            $branches = $this->branch->with('opportunities','manager')
            ->whereIn('id',$this->person->myBranches())
            ->get();
           
            return response()->view('opportunities.mgrindex',compact('branches'));
        } else{
       $activityTypes = $this->activity->activityTypes;
       $mybranches = array_keys($this->person->myBranches());
       $opportunities = $this->opportunity
                ->whereIn('branch_id',$mybranches)
                ->with('address','branch','address.activities')
                ->orderBy('branch_id')
                ->get();
        
        
        $branchorders = $this->branch->with('orders','orders.activities')->whereIn('id',$mybranches)->get(); 
        
        $leads = $this->branch->with('leads')->whereIn('id',$mybranches)->get();
 
        return response()->view('opportunities.index',compact('opportunities','activityTypes','branchorders','leads'));
        
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
