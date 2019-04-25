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
        if(! $this->period){
            $this->period = $this->activity->getPeriod();
        }
        $activityTypes = $activityTypes = ActivityType::all();
       
        
        $myBranches = $this->person->myBranches();
        if(! $myBranches){
            return redirect()->back()->withWarning("You are not assigned to any branches. Please contact Sales Operations");
        }
        $data = $this->getBranchData(array_keys($myBranches));
        $data['period'] = $this->period;
        return response()->view('opportunities.index', compact('data', 'activityTypes', 'myBranches','period'));
    }

    public function branchOpportunities(Branch $branch, Request $request)
    {
     

        if (request()->has('branch')) {
            $data = $this->getBranchData([request('branch')]);
        } else {
             $data = $this->getBranchData([$branch->id]);
        }

        $activityTypes = $activityTypes = ActivityType::all();
       
        $myBranches = $this->person->myBranches();

        return response()->view('opportunities.index', compact('data', 'activityTypes', 'myBranches'));
    }

    public function getSummaryBranchOpportunities(array $branches){
        
        return $this->branch
        
        ->withCount(
            'opportunities',
            'leads',
            'activities'
        )
        ->withCount(
            ['opportunities',
                    'opportunities as won'=>function ($query) {
            
                        $query->whereClosed(1);
                    },
                'opportunities as lost'=>function ($query) {
                    $query->whereClosed(2);
                }]
        )
    
           
        ->with('manager')
        ->getActivitiesByType()
        ->whereIn('id', $branches)
        ->get();
       
       // $data['activities'] = $this->branch->whereIn('id',$branches)->get();
        //$data['charts'] = $this->getChartData($branches);
    }
    public function getBranchData(array $branches)
    {
        $data['branches'] =$this->getBranches($branches);


        $data['opportunities'] = $this->getOpportunities($branches);

       

        $data['addresses'] = $data['opportunities']->map(function ($opportunity) {
            return $opportunity->address;
        });

        $data['activities'] = $data['addresses']->map(function ($address) {
            if ($address) {
                return $address->activities;
            }
        });
       
       
        return $data;
    }

       
       private function getBranches($branches)
       {
        return  $this->branch->with('opportunities', 'leads', 'manager')
            ->whereIn('id', $branches)
            ->get();
       }

       private function getOpportunities($branches)
       {
         
         return $this->opportunity
                ->whereIn('branch_id', $branches)
                ->with('address', 'branch', 'address.activities')
                ->thisPeriod($this->period)
                ->open($this->period)
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
     * @param  \Illuminate\Http\Request  $request
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
            ->firstOrCreate(['address_id'=>request('address_id'),'branch_id'=>request('branch_id')]);
        
        $data = request()->except('_token');
        
        if (request()->filled('expected_close')) {
            $data['expected_close'] = Carbon::parse($data['expected_close']);
        }
        if(in_array(request('closed'),['1','2'] )){
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
     * @param  \App\Opportunity  $opportunity
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
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function edit(Opportunity $opportunity)
    {
       
        return response()->view('opportunities.edit', compact('opportunity'));
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
        if ($data['expected_close']) {
            $data['expected_close'] = Carbon::parse($data['expected_close']);
        }
        if ($data['actual_close']) {
            $data['actual_close'] = Carbon::parse($data['actual_close']);
        }
        $opportunity->update($data);
        
        return redirect()->route('opportunity.index', $opportunity->address->address_id)->withMessage('Opportunity updated');
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
        return redirect()->route('address.show', $address)->withMessage('Opportunity deleted');
    }

    public function remove(Address $address, Request $request)
    {
        
        $address->assignedToBranch()->detach(request('branch_id'));
       
        return redirect()->route('branch.leads',request('branch_id'))->withMessage('Lead removed');
    }
    public function addToBranchLeads(Address $address, Request $request)
    {
 
        // need to remove from other branches
       // $address->assignedToBranch()->detach();
        //check if it exists
        $test = $this->addressbranch->where('address_id', '=', $address->id)->where('branch_id', '=', request('branch_id'))->get();
        if ($test->count()>0) {
            return redirect()->back()->withError($address->businessname .' is already on the branch '. request('branch_id'). ' leads list');
        }

        $address->assignedToBranch()->attach(request('branch_id'));
        return redirect()->back()->withMessage('Added to Branch Leads');
    }

    public function close(Request $request, $opportunity)
    {
        $data= request()->except('_token');
        $data['actual_close'] = Carbon::now();
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

        return redirect()->back()->withMessage('Opportunity closed');
    }
    public function toggle(Request $request)
    {
        $opportunity = $this->opportunity->findOrFail(request('id'));
        
        if ($opportunity->top50) {
            $opportunity->top50 = null;
        } else {
            $opportunity->top50 = 1;
        }
        $opportunity->save();
    }
    public function chart()
    {
        if (! $branch_ids = $this->person->myBranches()) {
            return redirect()->route('home')->withWarning('You are not associated with any branch');
        }
        $branches = array_keys($branch_ids);

        $data = $this->getChartData($branches);


        $data = $this->prepChartData($data);
     
    
        return response()->view('opportunities.chart', compact('data'));
    }

    private function getChartData($branches)
    {
        
        return  $this->branch
                    ->whereIn('id', $branches)
                    ->getActivitiesByType(4)
                    ->withCount('leads')
                    ->withCount(
                        ['opportunities',
                                'opportunities as won'=>function ($query) {
                        
                                    $query->whereClosed(1);
                                },
                            'opportunities as lost'=>function ($query) {
                                $query->whereClosed(2);
                            }]
                    )
                    ->get();
        /*return $this->addressbranch
            
            ->whereIn('branch_id',$branches)
            ->whereHas('activities',function($q){
               $q->whereBetween('activity_date',[Carbon::now()->subMOnth(2),Carbon::now()->addDay()]);
            })
            ->withCount(       
                    ['opportunities',
                        'opportunities as won'=>function($query){
                
                        $query->whereClosed(1);
                    },'opportunities as lost'=>function($query){
                
                        $query->whereClosed(2);
                    }]


                  )
            ->withCount('activities')
            ->groupBy('address_branch.branch_id')->get();*/
    }

    private function prepChartData($results)
    {


        $string = '';

        foreach ($results as $branch) {
          
          
            $string = $string . "[\"".$branch->branchname ."\",  ".$branch->activities->count() .",  ".$branch->opportunities_count.", ".$branch->won  ."],";
        }

        return $string;
    }
          



    private function getBranchNotes($branches)
    {

        return Note::whereHas('relatesToLocation', function ($q) use ($branches) {
            $q->whereHas('assignedToBranch', function ($q) use ($branches) {
                $q->whereIn('branch_id', $branches);
            });
        })->with('relatesToLocation', 'writtenBy', 'writtenBy.person')->get();
    }
    private function getBranchActivities($branches)
    {
        





        $query = "SELECT branches.id as id, activitytype_id as type, count(activities.id) as activities
            FROM `activities`, address_branch,branches
            where activities.address_id = address_branch.address_id
            and address_branch.branch_id = branches.id
            and activities.activity_date BETWEEN CAST('".Carbon::now()->subMOnth(1)."' AS DATE) AND CAST('".Carbon::now()."' AS DATE)
            and branches.id in (".implode(",", $branches).")
            group by id,activitytype_id";
        $activities =  \DB::select(\DB::raw($query));
        $result = [];
        foreach ($activities as $activity) {
            $result[$activity->id][$activity->type] = $activity->activities;
        }

        return $result;
    }

   public function pipeline()
    {
      $myBranches = $this->getMyBranches();

      $pipeline =$this->opportunity->getBranchPipeline(array_keys($myBranches));
      return response()->view('opportunities.pipeline',compact('pipeline','myBranches'));

    }
    private function getMyBranches()
    {
      
      if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')){
       
            return $this->branch->all()->pluck('branchname','id')->toArray();
        
        } else {
      
             return  $this->person->myBranches();
        }
    }

}
