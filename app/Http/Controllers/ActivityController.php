<?php

namespace App\Http\Controllers;

use App\Address;
use App\Activity;
use App\ActivityType;
use App\Chart;
use App\Contact;
use App\Branch;
use Excel;
use App\Exports\MyActivities;
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
    public $chart;

    /**
     * [__construct description]
     * 
     * @param Activity $activity [description]
     * @param Contact  $contact  [description]
     * @param Person   $person   [description]
     * @param Branch   $branch   [description]
     * @param Chart    $chart    [description]
     */
    public function __construct(
        Activity $activity, Contact $contact, Person $person, Branch $branch, Chart $chart
    ) {
        $this->activity = $activity;
        $this->contact = $contact;
        $this->person = $person;
        $this->branch = $branch;
        $this->chart = $chart;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        if (! $myBranches = $this->person->myBranches()) {
            return redirect()->back()
                ->withError('You are not assigned to any branches');
        }

        
        if (session('branch')) {
            $branch = $this->branch->findOrFail(session('branch'));

        } else {
            $branches = array_keys($myBranches);
            $branch = $this->branch->findOrFail(reset($branches));
            session(['branch'=>$branch->id]);
        }
    
      
        $data = $this->_getBranchActivities($branch);
        $activities = $data['activities'];
        $title= $data['branches']->first()->branchname . " activities";

        return response()->view(
            'activities.index', 
            compact('activities', 'data', 'title', 'myBranches')
        );
       
    }

    public function show(Activity $activity)
    {
        $activity->load('branch', 'user.person', 'relatesToAddress', 'type');
        return response()->view('activities.show', compact('activity'));
    }
    /**
     * [branchUpcomingActivities description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function branchUpcomingActivities(Branch $branch)
    {

        $myBranches = $this->person->myBranches();
        if (! ( $myBranches)  or ! in_array($branch->id, array_keys($myBranches))) {
            return redirect()->back()
                ->withError('You are not assigned to that branch');
        } else {
            $data = $this->_getBranchActivities($branch, $from = true);
      
            $title= $branch->branchname . " upcoming follow up activities";
    
            return response()->view(
                'activities.upcoming', 
                compact('data', 'myBranches', 'title')
            ); 
        }
    }
    /**
     * [branchActivities description]
     * 
     * @param Request $request [description]
     * @param Branch  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function branchActivities(Request $request, Branch $branch) 
    {
    
        if (request()->has('branch')) {
            $branch = $this->branch->findOrFail(request('branch'));
        }
        $myBranches = $this->person->myBranches();
       
        if (! ( $myBranches)  
            or ! in_array($branch->id, array_keys($myBranches))
        ) {
            return redirect()->back()
                ->withError('You are not assigned to any branches');
        }
       
         
        $data = $this->_getBranchActivities($branch, $from = false);
        
        $title= $data['branches']->first()->branchname . " activities";
        return response()->view(
            'activities.index',
            compact('data', 'myBranches', 'title')
        );
    }
    
   
    /**
     * [getBranchActivities description]
     * 
     * @param [type] $branch [description]
     * @param [type] $from   [description]
     * 
     * @return [type]         [description]
     */
    private function _getBranchActivities(Branch $branch,$from=null)
    {
        $data['activities'] = $this->activity->myBranchActivities([$branch->id])
            ->when(
                $from, function ($q) {
                    $q->where('activity_date', '>=', Carbon::now()->startOfDay())
                        ->whereNull('completed');
                }
            )
            ->where('activity_date', '>', now()->subMonths(3))
            ->with(
                'relatesToAddress', 'relatedContact', 'type', 'user'
            )->get();

        $data['branches'] =  $this->_getbranches([$branch->id]);

        $weekCount = $this->activity->myBranchActivities([$branch->id])
            ->sevenDayCount()
            ->where('completed', '=', 1)
            ->pluck('activities', 'yearweek')
            ->toArray();
      
        $data['summary'] = $this->activity->summaryData($weekCount);
        $activitytypes = $this->_getBranchActivititiesByType($branch);
        $data['activitychart'] = $this->chart->getBranchActivityByTypeChart($activitytypes);
        return $data;
    }
    /**
     * [_getBranches description]
     * 
     * @param Array $branches [description]
     * 
     * @return [type]           [description]
     */
    private function _getBranches(Array $branches)
    {
        return  $this->branch->with('manager')
            ->whereIn('id', $branches)
            ->get();
    }
   

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(ActivityFormRequest $request)
    {
      
        // can we detect the branch here?
        $data = $this->_parseData($request); 
       
        $activity = Activity::create($data['activity']);

        if (request()->filled('followup_date')) {
            // create a new activity
             $followup = Activity::create($data['followup']);
             
             $activity->followupActivity()->associate($followup);
             //$activity->followupActivity()->create($data['followup']);

      
        }
        if (request()->filled('contact')) {
            $activity->relatedContact()->attach($data['contact']['contact']);
        }
        $activity->load('relatedContact');
        if (request()->has('mobile')) {
            return redirect()->route('mobile.show', $data['activity']['address_id']);
        } else {
            return redirect()->route('address.show', $data['activity']['address_id']);
        }
        dd('Please report this error: Activity Controller # 229' . $activity->load('relatedActivity'));
    }

    /**
     * [complete description]
     * 
     * @param Activity $activity [description]
     * 
     * @return [type]             [description]
     */
    public function complete(Activity $activity)
    {
        $activity->update(['completed'=>1]);
        return redirect()->back();
    }
    /**
     * [_createFollowUpActivity description]
     * 
     * @param array    $data     [description]
     * @param Activity $activity [description]
     * 
     * @return [type]             [description]
     */
    private function _createFollowUpActivity(array $data,Activity $activity)
    {
   

        return Activity::create($data['followup']);
    }
    /**
     * [parseData description]
     * 
     * @param [type] $request [description]
     * 
     * @return [type]          [description]
     */
    private function _parseData(Request $request)
    {
        
        $data['activity'] = request()->only(
            [
                'activitytype_id', 
                'note', 
                'activity_date',
                'address_id', 
                'followup_date', 
                'branch_id'
            ]
        );
        
        // this should not be neccessary but some forms have
        // location id vs address id
        if (request()->has('location_id')) {
            $data['activity']['address_id'] = request('location_id');
        }
        $data['activity']['activity_date'] = Carbon::parse($data['activity']['activity_date']);
        $data['activity']['user_id'] = auth()->user()->id;
        // assume if activity date is in the past then completed
        if (request()->has('completed')) {
            $data['activity']['completed'] = 1;
        } elseif ($data['activity']['activity_date'] <= Carbon::now()) {
            $data['activity']['completed'] = 1;
        } else {
            $data['activity']['completed']=null;
        }
        // get follow up date
        if (request()->filled('followup_date')) {
            $data['activity']['followup_date'] = Carbon::parse($data['activity']['followup_date']);
            $data['followup'] = request()->only(['followup_id', 'address_id']);
            $data['followup']['note'] = " ";
            $data['followup']['activity_date'] = $data['activity']['followup_date'];
            $data['followup']['activitytype_id'] = request('followup_activity');
            $data['followup']['address_id'] = $data['activity']['address_id'];
            $data['followup']['branch_id']= request('branch_id');
            $data['followup']['followup_date'] = null;
            $data['followup']['user_id'] = auth()->user()->id;
        }

        $data['contact']= $request->only(['contact']);
        
        return $data;
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Activity $activity [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        $location = Address::with('contacts', 'activities')
            ->findOrFail($activity->address_id);
        $activities = ActivityType::orderBy('sequence')
            ->pluck('activity', 'id')->toArray();
        return response()->view(
            'activities.edit', 
            compact('activity', 'activities', 'location')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request  [description]
     * @param \App\Activity            $activity [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(ActivityFormRequest $request, Activity $activity)
    {
        
        $activity->load('followupActivity');
        $data = $this->_parseData($request);
        
        $activity->update($data['activity']);
        if (request()->filled('followup_date')) {
            
            if ($activity->followupActivity) {
                $activity->followupActivity->update($data['followup']);
            } else {
               
                $followup = Activity::create($data['followup']);

                $activity->followupActivity()->associate($followup);
            } 
            
            
        }
   
        return redirect()->route('address.show', $activity->address_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Activity $activity [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        $address = $activity->address_id;
        $activity->delete();
        return redirect()->route('address.show', $address)
            ->withMessage('Activity deleted');
    }
    /**
     * [export description]
     * 
     * @return [type] [description]
     */
    public function export()
    {
        return Excel::download(new MyActivities(), auth()->user()->person->fullName() . ' Activities.csv');
    }
    
    /**
     * [future description]
     * 
     * @return [type] [description]
     */
    public function future()
    {
      
        $activities = $this->activity->myActivity()
            ->where('followup_date', '>=', Carbon::now())
            ->with('relatesToAddress', 'relatedContact', 'type')
            ->get();
       
        return response()->view('activities.index', compact('activities'));
    }
    /**
     * [_getBranchActivititiesByType description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    private function _getBranchActivititiesByType($branch)
    {
       
        if (! $this->period) {
            $this->period['from'] = Carbon::now()->subWeeks(4)->startOfWeek();
            $this->period['to'] = Carbon::now()->endOfWeek();
        }
        return $this->activity->whereIn('branch_id', [$branch->id])
            ->periodActivities($this->period)
            ->completed()
            ->typeDayCount()
            ->get();
    }
    /**
     * [getBranchActivtiesByType description]
     * 
     * @param [type] $branch       [description]
     * @param [type] $activitytype [description]
     * 
     * @return [type]               [description]
     */
    public function getBranchActivtiesByType($branch, $activitytype = null)
    {

        if ($activitytype) {
            $activitytype = ActivityType::findOrFail($activitytype);
        }
        $branch = $branch->getActivitiesByType($activitytype)
            ->findOrFail($branch->id);
   
        return response()->view(
            'opportunities.showactivities', compact('branch', 'activitytype')
        );
    }

    /**
     * [updateNote description]
     * 
     * @param Activity $activity [description]
     * @param Request  $request  [description]
     * 
     * @return [type]             [description]
     */
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
