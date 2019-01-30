<?php

namespace App\Http\Controllers;

use App\Opportunity;
use App\Lead;
use App\Company;
use App\Person;
use App\Branch;
use App\Orders;
use App\Contact;
use App\Address;
use App\Activity;
use \Carbon\Carbon;
use App\ActivityType;
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
    public $contact;

    public function __construct(Opportunity $opportunity, Orders $orders, Branch $branch, Person $person, Address $address,Activity $activity,Lead $leads,Contact $contact){
        $this->opportunity = $opportunity;
        $this->person = $person;
        $this->activity = $activity;
        $this->branch = $branch;
        $this->address = $address;
        $this->orders = $orders;
        $this->leads = $leads;
        $this->contact = $contact;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $activityTypes = ActivityType::all();
        if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')){

             $myBranches = $this->branch->all()->pluck('branchname','id')->toArray();
             $data = $this->getSummaryBranchOpportunities(array_keys($myBranches));

             return response()->view('opportunities.mgrindex',compact('data','activityTypes'));
           
             
        }else{
             $myBranches = $this->person->myBranches();
        }
      
        if(count($myBranches)==0){
            return redirect()->route('user.show',auth()->user()->id)->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }
        if((! auth()->user()->hasRole('branch_manager') && $this->person->myTeam()->count() >1 )){
             
                     $data = $this->getSummaryBranchOpportunities(array_keys($myBranches));
                   
            // need to get all the activities esp conversions / closes
            return response()->view('opportunities.mgrindex',compact('data','activityTypes'));
        } else{
               
                      $data = $this->getBranchOpportunities([array_keys($myBranches)[0]]);
                      
                      return response()->view('opportunities.index',compact('data','activityTypes','myBranches'));

        }
        return redirect()->route('user.show',auth()->user()->id)->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        // if no branches abort
        // if no branches then select branc / Sales OPs
    }

    public function branchOpportunities(Branch $branch, Request $request){

       if(request()->has('branch')){
            $data = $this->getBranchOpportunities([request('branch')]);
       }else{
             $data = $this->getBranchOpportunities([$branch->id]);
       }
       $activityTypes = $activityTypes = ActivityType::all();
       
       $myBranches = $this->person->myBranches();
       
        return response()->view('opportunities.index',compact('data','activityTypes','myBranches'));
    }

    public function getSummaryBranchOpportunities(array $branches){

        $data['branches'] = $this->branch->withCount('opportunities','leads')->with('manager')
            ->whereIn('id',$branches)
            ->get(); 

        $data['activities'] = $this->getBranchActivities($branches);

        return $data;


    }
    public function getBranchOpportunities(array $branches){
        
        $data['branches'] = $this->branch->with('opportunities','leads','manager')
            ->whereIn('id',$branches)
            ->get();
        $data['opportunities'] = $this->opportunity
                ->whereIn('branch_id',$branches)
                ->with('address','branch','address.activities')
                ->orderBy('branch_id')
                ->distinct()
                ->get();

        $data['addresses'] = $data['opportunities']->map(function ($opportunity){
            return $opportunity->address;
        });

        $data['activities'] = $data['addresses']->map(function ($address){
            return $address->activities->load('relatesToAddress','relatedContact');
        });
        
        $data['branchorders'] = $this->branch->with('orders','orders.address')->whereIn('id',$branches)->get(); 
 
        $data['leads'] = $this->branch->with('leads','leads.leadsource')->whereIn('id',$branches)->get();
        $opportunity = $data['opportunities']->pluck('address_id')->toArray();
        $customer = $data['branchorders']->pluck('address_id')->toArray();
        
        $data['contacts'] = $this->contact->whereIn('address_id',array_merge($opportunity,$customer))->with('location')->get();
        return $data;
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
        
        $opportunity = $this->opportunity->create(request()->all());
        $address = $this->address->findOrFail(request('address_id'));
        $address->assignedToBranch()->detach();
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
        return redirect()->route('address.show',$address->id);
        
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

    public function close(Request $request, $address){
        
        $opportunity = $this->opportunity->findOrFail(request('opportunity_id'));
        
        if(request('close')=='converted'){
            $address->load('company');
            if(! $address->company or $address->company->customer_id != request('client_id')){
              
               $company = Company::create(['companyname'=>$address->businessname,'accounttypes_id'=>3,'customer_id'=>request('client_id')]);
               
               $address->update(['company_id' => $company->id]);
               
            }
            // update opportunity record
            
            $data = ['closed'=>1,'client_ref'=>request('client_id'),'requirements'=>request('requirements'),'value'=>request('periodbusiness')];
            $opportunity->update($data); // create an activity on the address
            $data=['address_id'=>$address->id,'user_id'=>auth()->user()->id,
            'note'=>request('comments'),'activity_date'=>Carbon::now(),'activitytype_id'=>'8'];
            $activity = Activity::create($data);
            // store the values on the opportunity
        }else{
            //create an activity on the address
            // remove from opportunity
            
            $data=['address_id'=>$address->id,'user_id'=>auth()->user()->id,
            'note'=>request('comments'),'activity_date'=>Carbon::now(),'activitytype_id'=>'9'];
            $activity = Activity::create($data);
            $address->activities()->save($activity);
            $opportunity->update(['closed'=>2]);
            
        }
        return redirect()->route('opportunity.index')->withMessage('Opportunity '. request('close'));
        
    }
    public function toggle(Request $request){
        $opportunity = $this->opportunity->findOrFail(request('id'));
        
        if($opportunity->top50){
            $opportunity->top50 = null;
        }else{
            $opportunity->top50 = 1;
        }
        $opportunity->save();
    }
    
    private function getBranchActivities($branches){
        $query = "SELECT branches.id as id, activitytype_id as type, count(activities.id) as activities
            FROM `activities`, address_branch,branches
            where activities.address_id = address_branch.address_id
            and address_branch.branch_id = branches.id
            and activities.activity_date BETWEEN CAST('".Carbon::now()->subMOnth(1)."' AS DATE) AND CAST('".Carbon::now()."' AS DATE)
            and branches.id in (".implode(",",$branches).")
            group by id,activitytype_id";
        $activities =  \DB::select(\DB::raw($query));
        $result = array();
        foreach ($activities as $activity){
            $result[$activity->id][$activity->type] = $activity->activities;
        }
        return $result;

    }
}
