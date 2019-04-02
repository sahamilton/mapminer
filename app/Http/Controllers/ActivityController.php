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
       
        return response()->view('activities.index', compact( 'data','title','myBranches'));
       
    }
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
    


    private function getBranchActivities($branch,$from=null)
    {
      
        $team = $this->person->myBranchTeam([$branch->id])->toArray();
            

        $data['activities'] = $this->activity->myTeamsActivities($team);
        if($from){
            $data['activities']= $data['activities']->where('followup_date','>=',now());
        }
        $data['activities'] =  $data['activities']->with('relatesToAddress', 'relatedContact', 'type', 'user')->get();

        $data['branches'] =  $this->getbranches([$branch->id]);
        if(! $this->period){
            $this->period = $this->activity->getPeriod();
        }
        $weekCount = $this->activity->myTeamsActivities($team)
                ->whereCompleted(1)
                ->thisPeriod($this->period)
                ->sevenDayCount()
                ->get();
        $data['chart'] = $this->getActivityChart($weekCount);
        $data['summary'] = $this->activity->summaryData($weekCount);

        
        return $data;
    }

    private function getActivityChart($weekCount)
    {
        $activityTypes = \App\ActivityType::all();
        foreach ($weekCount as $week){
            $chart[$activityTypes->where('id','=',$week->activitytype_id)->first()->activity][$week->yearweek]= $week->activities;
            
        }
        $periods = ['201909','201910','201911','201912','201913'];
        foreach ($chart as $key=>$value){
           
            foreach ($periods as $period){
                if(! array_key_exists($period, $value)){
                    $charts[$key][$period]=0;
                }else{
                    $charts[$key][$period] = $value[$period];
                }
            }
            ksort($charts[$key]);
        }
        
        // fill missing periods
        dd($charts,$this->period);
    }
    private function getBranches(Array $branches)
       {
        return  $this->branch->with( 'manager')
            ->whereIn('id', $branches)
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
    public function store(ActivityFormRequest $request)
    {
       
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


    public function complete(Activity $activity)
    {
        $activity->update(['completed'=>1]);
        return redirect()->back();
    }

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
        $data['activity'] = request()->only(['activitytype_id','note','activity_date','address_id','followup_date']);
        if(request()->has('completed')){
            $data['activity']['completed']=1;
        }else{
            $data['activity']['completed']=null;
        }
        // this should not be neccessary but some forms have
        // location id vs address id
        if(request()->has('location_id')){
            $data['activity']['address_id'] = request('location_id');
        }
        $data['activity']['activity_date'] = Carbon::parse($data['activity']['activity_date']);

        // get follow up date 

        if (request()->filled('followup_date')){
            $data['activity']['followup_date'] = Carbon::parse($data['activity']['followup_date']);
            $data['followup'] = request()->only(['followup_id','note','address_id']);
            $data['followup']['activity_date'] = $data['activity']['followup_date'];
            $data['followup']['activitytype_id'] = request('followup_id');
            $data['followup']['address_id'] = request('address_id');
            $data['followup_date']['followup_date'] = null;
        }
    // contact data
        $data['contact']= $request->only(['contact']);
        $data['user_id'] = auth()->user()->id;
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {


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

    public function seeder()
    {
        //first get branches
        // then get addresses
        //random create activity from random type
    }

    public function future()
    {
      
        $activities = $this->activity->myActivity()->where('followup_date', '>=', Carbon::now())->with('relatesToAddress', 'relatedContact', 'type')->get();
       
        return response()->view('activities.index', compact('activities'));
    }

    public function getBranchActivtiesByType($branch, $activitytype = null)
    {

        if ($activitytype) {
            $activitytype = ActivityType::findOrFail($activitytype);
        }
        $branch = $branch->getActivitiesByType($activitytype)->findOrFail($branch->id);
   
        return response()->view('opportunities.showactivities', compact('branch', 'activitytype'));
    }
}
