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

        public $person;


    public function __construct(
            Activity $activity,   
            Address $address,
            AddressBranch $addressbranch,
            Branch $branch, 
            Contact $contact,
            Opportunity $opportunity,
            Person $person
        ){
        $this->address = $address;
        $this->addressbranch = $addressbranch;
        $this->branch = $branch;
        $this->contact = $contact;
        $this->opportunity = $opportunity;
        $this->person = $person; 
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

        $data['branches'] = $this->branch
        
        ->withCount('opportunities',
            'leads')
        ->withCount(       
                ['opportunities',
                    'opportunities as won'=>function($query){
            
                    $query->whereClosed(1);
                },
                'opportunities as lost'=>function($query){
                    $query->whereClosed(2);
                }]
            )
    
           
        ->with('manager')
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
            if($address)
            {
                return $address->activities;
            }
            
        });
       
        $data['branchorders'] = $this->branch->with('orders','orders.address')->whereIn('id',$branches)->get(); 
 
        $data['leads'] = $this->branch->with('leads','leads.leadsource','leads.createdBy')

                        ->whereIn('id',$branches)->get();
                   
        $opportunity = $data['opportunities']->pluck('address_id')->toArray();
        $customer = $data['branchorders']->pluck('address_id')->toArray();
        
        $data['contacts'] = $this->contact->whereIn('address_id',array_merge($opportunity,$customer))->with('location')->get();

        $data['notes'] = $this->getBranchNotes($branches);
      
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
    public function store(OpportunityFormRequest $request)
    {
        
        //nned to remove it from all the other branches when an oppty is created
       $address = $this->address->findOrFail(request('address_id'));
       $address->assignedToBranch()->sync([request('branch_id')]);
        // make sure that the relationship exists
        $join = $this->addressbranch
            ->where('address_id','=',request('address_id'))
            ->where('branch_id','=',request('branch_id'))
            ->firstOrCreate(['address_id'=>request('address_id'),'branch_id'=>request('branch_id')]);
        
        $data = request()->except('_token');
        if($data['expected_close']){
            $data['expected_close'] = Carbon::parse($data['expected_close']);
        }
        $data['user_id'] = auth()->user()->id;

        $join->opportunities()->create($data);

        return redirect()->back()->withMessage("Added to branch opportunities");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function show(Opportunity $opportunity)
    {
        $opportunity->load('branch','address');
        
      
        return response()->view('opportunities.show',compact('opportunity'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function edit(Opportunity $opportunity)
    {
       
        return response()->view('opportunities.edit',compact('opportunity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function update(OpportunityFormRequest $request, Opportunity $opportunity)
    {
        $data = request()->except(['_token','_method','submit']);
        $data['user_id'] = auth()->user()->id;
        if($data['expected_close']){
            $data['expected_close'] = Carbon::parse($data['expected_close']);
        }
        $opportunity->update($data);
        
        return redirect()->route('address.show',$opportunity->address->address_id)->withMessage('Opportunity updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Opportunity $opportunity)
    {
        
        $address = $opportunity->address->address_id;
        $opportunity->delete();
        return redirect()->route('address.show',$address)->withMessage('Opportunity deleted');
    }

    public function remove(Address $address, Request $request)
    {
        $address->assignedToBranch()->detach(request('branch-id'));
        return redirect()->back()->withMessage('lead removed');
    }
    public function addToBranchLeads(Address $address, Request $request){
 
        // need to remove from other branches
       // $address->assignedToBranch()->detach();
        //check if it exists
        $test = $this->addressbranch->where('address_id','=',$address->id)->where('branch_id','=',request('branch_id'))->get();
        if($test->count()>0){
         
            return redirect()->back()->withError( $address->businessname .' is already on the branch '. request('branch_id'). ' leads list');
        }

        $address->assignedToBranch()->attach(request('branch_id'));
        return redirect()->back()->withMessage('Added to Branch Leads');
    }

    public function close(Request $request, $opportunity){
        $data= request()->except('_token');
        $data['actual_close'] = CArbon::now();
        $opportunity->update($data);
        $opportunity->load('address','address.address','address.address.company');
            // check to see if the client_id exists else create new company
            if(request()->filled('client_id')){
               $company = Company::where('client_id','=',request('client_id'))
               ->firstOrCreate(
                [
                  'companyname'=>$address->address->businessname,
                  'accounttypes_id'=>3,
                  'customer_id'=>request('client_id')
                ]);
               $address->update(['company_id' => $company->id]);
            }

        return redirect()->back()->withMessage('Opportunity closed');
        
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


    private function getBranchNotes($branches){

        return Note::whereHas('relatesToLocation',function($q) use ($branches){
            $q->whereHas('assignedToBranch',function ($q) use($branches){
                $q->whereIn('branch_id',$branches);
            });
        })->with('relatesToLocation','writtenBy','writtenBy.person')->get();






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
