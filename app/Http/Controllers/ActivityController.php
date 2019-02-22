<?php

namespace App\Http\Controllers;
use App\Address;
use App\Activity;
use App\ActivityType;
use App\Contact;
use App\Person;
use App\AddressBranch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\ActivityFormRequest;

class ActivityController extends Controller
{
    public $activity;
    public $contact;

    public function __construct(Activity $activity,Contact $contact, Person $person){
        $this->activity = $activity;
        $this->contact = $contact;
        $this->person = $person;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $team = $this->person->where('user_id','=',auth()->user()->id)->first()->descendantsAndSelf()->pluck('user_id')->toArray();
        if(count($team)>1){
            $activities = $this->activity->myTeamsActivities($team)->with('relatesToAddress','relatedContact','type','user')->get();
            $weekCount = $this->activity->myTeamsActivities($team)->sevenDayCount()->pluck('activities','yearweek')->toArray(); 
        }else{
           
           $activities = $this->activity->myActivity()->with('relatesToAddress','relatedContact','type','user')->get();
          
           $weekCount = $this->activity->myActivity()->sevenDayCount()->pluck('activities','yearweek')->toArray(); 
        }
        
        $data = $this->activity->summaryData($weekCount);
        
       
        return response()->view('activities.index',compact('activities','data'));
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
    public function store(ActivityFormRequest $request){
    
        $data = $this->parseData($request);
     
        $activity = Activity::create($data);

        if(isset($data['contact'])){
            
            $activity->relatedContact()->attach($data['contact']);
        }
        $activity->load('relatedContact');
        return redirect()->route('address.show',$data['address_id']);
    }

    private function parseData($request){
        $data= $request->except(['_token','submit']);
        $data['activity_date'] = Carbon::parse($data['activity_date']);
        if($data['followup_date']){
            $data['followup_date'] = Carbon::parse($data['followup_date']);
        }
        if(isset($data['location_id'])){
            $data['address_id'] =$data['location_id'];
        }else{
            $data['address_id'] = $data['address_id'];
        }
        $data['activitytype_id'] = $data['activity'];
        $data['user_id'] = auth()->user()->id;
        $data['contact_id'] = request('contact_id');
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
        $location = Address::with('contacts','activities')->findOrFail($activity->address_id);
        $activities = ActivityType::orderBy('sequence')->pluck('activity','id')->toArray();
        return response()->view('activities.edit', compact('activity','activities','location'));
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
       
        $activity->update($data);
   
        return redirect()->route('address.show',$activity->address_id);
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
        return redirect()->route('address.show',$address)->withMessage('Activity deleted');
    }

    public function seeder(){
        //first get branches
        // then get addresses
        //random create activity from random type
    }

    public function future(){
      
        $activities = $this->activity->myActivity()->where('followup_date','>=',Carbon::now())->with('relatesToAddress','relatedContact','type')->get();
       
        return response()->view('activities.index',compact('activities'));

    }

    public function getBranchActivtiesByType($branch,$activitytype= null){

        if($activitytype){
            $activitytype = ActivityType::findOrFail($activitytype);
        }
        $branch = $branch->getActivitiesByType($activitytype)->findOrFail($branch->id);
   
        return response()->view('opportunities.showactivities',compact('branch','activitytype'));
    }
}
