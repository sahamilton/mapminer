<?php

namespace App\Http\Controllers;

use App\Address;
use App\Activity;
use App\ActivityType;
use App\Contact;
use App\Branch;
use App\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\ActivityFormRequest;

class ActivityController extends Controller
{
    public $activity;
    public $contact;
    public $branch;
    public $period;

    public function __construct(Activity $activity, Contact $contact, Person $person, Branch $branch)
    {
        $this->activity = $activity;
        $this->contact = $contact;
        $this->person = $person;
        $this->branch = $branch;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     

            if(! $myBranches = $this->person->myBranches()){
                return redirect()->back()->withError('You are not assigned to any branches');
           }

            $branches = array_keys($myBranches);
            $branch = $this->branch->findOrFail(reset($branches));

            $data = $this->getBranchActivities($branch);
     
            $title= $data['branches']->first()->branchname . " activities";
  
        return response()->view('activities.index', compact('activities', 'data','title','myBranches'));
       
    }
    /**
     * [branchUpcomingActivities description]
     * @param  Branch $branch [description]
     * @return [type]         [description]
     */
    public function branchUpcomingActivities(Branch $branch){

        $myBranches = $this->person->myBranches();
        if(! ( $myBranches)  or ! in_array($branch->id,array_keys($myBranches))){
            return redirect()->back()->withError('You are not assigned to that branch');
        }else{
            $data = $this->getBranchActivities($branch,$from = true);
      
        $title= $branch->branchname . " upcoming follow up activities";
        
        return response()->view('activities.upcoming', compact('data', 'myBranches','title')); 
        }
    }
    /**
     * [branchActivities description]
     * @param  Request $request [description]
     * @param  Branch  $branch  [description]
     * @return [type]           [description]
     */
    public function branchActivities(Request $request, Branch $branch){
        
        if (request()->has('branch')) {
            $branch = $this->branch->findOrFail(request('branch'));
        }
        $myBranches = $this->person->myBranches();
       
        if(! ( $myBranches)  or ! in_array($branch->id,array_keys($myBranches))){
            return redirect()->back()->withError('You are not assigned to any branches');
       }
       
         
        $data = $this->getBranchActivities($branch,$from = false);
       
        $title= $data['branches']->first()->branchname . " activities";
        return response()->view('activities.index', compact('data', 'myBranches','title'));
    }
    
   
    /**
     * [getBranchActivities description]
     * @param  [type] $branch [description]
     * @param  [type] $from   [description]
     * @return [type]         [description]
     */
    private function getBranchActivities($branch,$from=null)
    {
       
       

        $data['activities'] = $this->activity->myBranchActivities([$branch->id]); 
        if($from){
            $data['activities']= $data['activities']
            ->where('activity_date','>=',Carbon::now()->startOfDay())
            ->whereNull('completed');
        }

        $data['activities'] =  $data['activities']->with('relatesToAddress', 'relatedContact', 'type', 'user')->get();

        $data['branches'] =  $this->getbranches([$branch->id]);

        $weekCount = $this->activity->myBranchActivities([$branch->id])
            ->sevenDayCount()
            ->where('completed','=',1)
            ->pluck('activities', 'yearweek')
            ->toArray();
        
        $data['summary'] = $this->activity->summaryData($weekCount);
       //dd($data['summary']);
        
        return $data;
    }

    private function getBranches(Array $branches)
       {
        return  $this->branch->with( 'manager')
            ->whereIn('id', $branches)
            ->get();
       }
    /**
     * [yearWeekBetween description]
     * @param  [type] $from [description]
     * @param  [type] $to   [description]
     * @return [type]       [description]
     */
    private function yearWeekBetween(Carbon $from=null,Carbon $to=null)
    {

      if(! $from){
        $from = clone($this->period['from']);
      }
      if(! $to){
        $to = clone($this->period['to']);
      }
      
      

      $keys=[];
      for($i = $from->startOfWeek()->format('Y-m-d'); $i<= $to->startOfWeek()->format('Y-m-d');$i = $from->addWeek()->format('Y-m-d')){
        $keys[]=$i;
      }

      return $keys;
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
    public function store(ActivityFormRequest $request)
    {
    
        // can we detect the branch here?
        $data = $this->parseData($request);
        $activity = Activity::create($data['activity']);

        if(request()->filled('followup_date')){
            // create a new activity
             $relatedActivity = $this->createFollowUpActivity($data,$activity);
             
             $activity->update(['relatedActivity'=>$relatedActivity->id]);
        }
        if (request()->filled('contact')) {
            $activity->relatedContact()->attach($data['contact']['contact']);
        }
        $activity->load('relatedContact');
        return redirect()->route('address.show', $data['activity']['address_id']);
    }

    /**
     * [complete description]
     * @param  Activity $activity [description]
     * @return [type]             [description]
     */
    public function complete(Activity $activity)
    {
        $activity->update(['completed'=>1]);
        return redirect()->back();
    }
    /**
     * [createFollowUpActivity description]
     * @param  array    $data     [description]
     * @param  Activity $activity [description]
     * @return [type]             [description]
     */
    private function createFollowUpActivity(array $data,Activity $activity)
    {
   

        return Activity::create($data['followup']);
    }
    /**
     * [parseData description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    private function parseData(Request $request)
    {
        
       // get activity data
        $data['activity'] = request()->only(['activitytype_id','note','activity_date','address_id','followup_date','branch_id']);
        
        // this should not be neccessary but some forms have
        // location id vs address id
        if(request()->has('location_id')){
            $data['activity']['address_id'] = request('location_id');
        }
        $data['activity']['activity_date'] = Carbon::parse($data['activity']['activity_date']);
        $data['activity']['user_id'] = auth()->user()->id;
        // assume if activity date is in the past then completed
        if(request()->has('completed')){
            $data['activity']['completed']=1;
        }elseif($data['activity']['activity_date'] <= Carbon::now()){
            $data['activity']['completed']=1;
        }else{
            $data['activity']['completed']=null;
        }
        // get follow up date
        if (request()->filled('followup_date')){
            $data['activity']['followup_date'] = Carbon::parse($data['activity']['followup_date']);
            $data['followup'] = request()->only(['followup_id','address_id']);
            $data['followup']['note'] = " ";
            $data['followup']['activity_date'] = $data['activity']['followup_date'];
            $data['followup']['activitytype_id'] = request('followup_activity');
            $data['followup']['address_id'] = request('address_id');
            $data['followup']['branch_id']= request('branch_id');
            $data['followup']['followup_date'] = null;
            $data['followup']['user_id'] = auth()->user()->id;
        }
    // contact data
        $data['contact']= $request->only(['contact']);
        
        return $data;
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        $location = Address::with('contacts', 'activities')->findOrFail($activity->address_id);
        $activities = ActivityType::orderBy('sequence')->pluck('activity', 'id')->toArray();
        return response()->view('activities.edit', compact('activity', 'activities', 'location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(ActivityFormRequest $request, Activity $activity)
    {
        
        
        $data = $this->parseData($request);
       
        $activity->update($data['activity']);
   
        return redirect()->route('address.show', $activity->address_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        $address = $activity->address_id;
        $activity->delete();
        return redirect()->route('address.show', $address)->withMessage('Activity deleted');
    }

    
    /**
     * [future description]
     * @return [type] [description]
     */
    public function future()
    {
      
        $activities = $this->activity->myActivity()->where('followup_date', '>=', Carbon::now())->with('relatesToAddress', 'relatedContact', 'type')->get();
       
        return response()->view('activities.index', compact('activities'));
    }
    /**
     * [getBranchActivtiesByType description]
     * @param  [type] $branch       [description]
     * @param  [type] $activitytype [description]
     * @return [type]               [description]
     */
    public function getBranchActivtiesByType($branch, $activitytype = null)
    {

        if ($activitytype) {
            $activitytype = ActivityType::findOrFail($activitytype);
        }
        $branch = $branch->getActivitiesByType($activitytype)->findOrFail($branch->id);
   
        return response()->view('opportunities.showactivities', compact('branch', 'activitytype'));
    }

    private function formatActivityChartData($data){
        $chartdata=[];
       
        $colors = $this->activity->createColors(12);
        $n=0;
        foreach ($data as $key => $value) {
            
                $chartdata[$key]['color'] = $colors[$n];
                $n++;
                $chartdata[$key]['labels']=implode("','", array_keys($value));
                $chartdata[$key]['data']=implode(",", array_values($value));
           
        }

        return $chartdata;
    }

    public function updateNote(Activity $activity, Request $request) 
    {
        
        $activity->update(['note'=>request('note')]);
        $response = array(
                'status' => 'success',
                'msg' => 'Note updated successfully',
            ); 
       
        return response()->json($response);
    }
}
