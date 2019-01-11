<?php

namespace App\Http\Controllers;

use App\Opportunity;
use App\Lead;
use App\Company;
use App\Person;
use App\Branch;
use App\Orders;
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
        $activityTypes = ActivityType::all();
        $myBranches = array_keys($this->person->myBranches());
       
        if(! auth()->user()->hasRole('Branch Manager') && $this->person->myTeam()->count() >1){
             $data = $this->getMarketManagerData($myBranches);
            // need to get all the activities esp conversions / closes
            return response()->view('opportunities.mgrindex',compact('data','activityTypes'));
        } else{
          
             $data = $this->getBranchOpportunities($myBranches);

            return response()->view('opportunities.index',compact('data','activityTypes'));
        
        }
        // if no branches abort
        // if no branches then select branc / Sales OPs
    }

    public function branchOpportunities($branch_id){

       $activityTypes = $activityTypes = ActivityType::all();
       $data = $this->getBranchOpportunities([$branch_id]);
      
       
        return response()->view('opportunities.index',compact('data','activityTypes'));
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

        
        $data['branchorders'] = $this->branch->with('orders','orders.activities')->whereIn('id',$branches)->get(); 
        
        $data['leads'] = $this->branch->with('leads','leads.leadsource')->whereIn('id',$branches)->get();

        return $data;
    }


    private function getMarketManagerData(array $branches){

        $data = $this->getBranchOpportunities($branches);
        $query = "SELECT opportunities.branch_id as branch,activities.activitytype_id as type, count(*) as sum
                FROM activities,addresses ,opportunities
                WHERE activities.address_id = addresses.id
                and opportunities.branch_id in (" . implode(",",$branches) . ") and addresses.id = opportunities.address_id
                group by branch, type";
        $data['summary'] = \DB::select(\DB::raw($query));
        foreach ($data['summary'] as $stats){

            $data['stats'][$stats->branch][$stats->type] = $stats->sum; 
        }
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
        
        $this->opportunity->create(request()->all());
        $address = $this->address->findOrFail(request('address_id'));
        if($address->addressable_type == 'lead'){
                $address->branchLead()->detach([$opportunity->branch_id]);
        }
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

        $location = $address->load($address->addressable_type,'contacts','company','industryVertical','activities',$address->addressable_type . '.relatedNotes','ranking');
        
        $ranked = $address->getMyRanking($location->ranking);

        $branches = $this->branch->nearby($location,100,5)->get();
        $rankingstatuses = $this->address->getStatusOptions;
        $people = $this->person->salesReps()->PrimaryRole()->nearby($location,100,5)->get();
               return response()->view('addresses.show',compact('location','branches','rankingstatuses','people','ranked'));
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

    public function close(Request $request, $address){
        
        $opportunity = $this->opportunity->findOrFail(request('opportunity_id'));
      
        if(request('close')=='convert'){
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
        
        if($opportunity->top5){
            $opportunity->update(['top50' => null]);
        }else{
            $opportunity->update(['top50' => 1]);
        }
       
    }

    
}
