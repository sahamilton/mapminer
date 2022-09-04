<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;   
use \Fractal;
use App\Transformers\EventTransformer;
use App\Models\Activity;
use App\Models\Person;
use App\Models\Branch;
use Illuminate\Http\Request;
use Redirect,Response;
use Carbon\Carbon;
   
class CalendarController extends Controller
{

    public $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }
    /**
     * [index description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        
        
        if (request()->has('start') && request()->has('end')) {
            $period['from'] = Carbon::parse(request('start'));
            $period['to'] = Carbon::parse(request('end'));
        } else {
            $period = $this->activity->getPeriod();
            $period['from'] = $period['from']->startOfWeek();
            $period['to'] = $period['to']->endOfWeek();
        }
        
        return $this->_getEventsToJson($period, $request);
       
         
    }
    
    /**
     * [create description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function create(Request $request)
    {  
        
        $insertArr = [ 'title' => $request->title,
                       'activity_date' => $request->start
                    ];
        $event = Activity::insert($insertArr);   
        return Response::json($event);
    }
    public function getCalPeriod($period)
    {
   
        $calperiod = $this->activity->getPeriod($period);

        return $this->_getEventsToJson($calperiod);
    }
    /**
     * [update description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function update(Request $request)
    {   
        $where = array('id' => $request->id);
        $updateArr = ['title' => $request->title,'start' => $request->start];
        $event  = Event::where($where)->update($updateArr);
 
        return Response::json($event);
    } 
 
    /**
     * [destroy description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function destroy(Request $request)
    {
        $event = Activity::where('id', $request->id)->delete();
   
        return Response::json($event);
    }    
    /**
     * [_getEventsToJson description]
     * 
     * @param Array $period [description]
     * 
     * @return [type]         [description]
     */
    private function _getEventsToJson(Array $period, Request $request)
    {
        $me = $this->_getMyInfo(request('branch'));
  
        $filters = [
            'branch' => request('branch'),
            'status'=>request('status'), 
            'type'=>request('type'), 
            'team'=>request('team')
        ];
      
        $activities = Activity::with('relatesToAddress', 'type')
            ->when(
                $filters['type']!='All', function ($q) use ($filters) {
                    $q->where('activitytype_id', $filters['type']);

                }
            )
            ->when(
                $filters['status'] !='All', function ($q) use ($filters) {
                    $q->when(
                        $filters['status'] == 2, function ($q) use ($filters) {
                            $q->whereNull('completed');
                        }, function ($q) {
                            $q->whereNotNull('completed');
                        }
                    );
                }
            )
            ->when(
                $filters['team'] != 'All', function ($q) use ($filters, $me) {
                    $q->where('user_id', $filters['team']);
                }, function ($q) use ($me) {
                    $q->whereIn('user_id', $me['team']);
                }
            )
            ->when(
                $filters['branch'] != 'all',  function ($q) use ($filters, $me) {
                    $q->where('branch_id', $filters['branch']);
                }, function ($q) use ($me) {
                     $q->whereIn('branch_id', $me['branches']);
                }
            )  
            ->whereBetween('activity_date', [$period['from'], $period['to']])
            
            ->get();
            
        $activities =  \Fractal::create()->collection($activities)->transformWith(EventTransformer::class)->toArray();
        
        return $activities['data'];
    }

    /**
     * [_getMyInfo description]
     * 
     * @return [type] [description]
     */
    private function _getMyInfo($branch_id)
    {

        
        $data['branches'] = Person::find(auth()->user()->person->id)->getMyBranches();
        $data['team'] = Branch::with('branchTeam')
            ->when(
                $branch_id != 'all', function ($q) use ($branch_id) {
                    $q->whereIn('branches.id', [$branch_id]);
                }, function ($q) use ($data) {
                    $q->whereIn('branches.id', $data['branches']);
                }
            )
            ->get()->flatMap(
                function ($branch) {
                    return $branch->branchTeam;
                }
            )->unique('id')
            ->pluck('user_id')
            ->toArray();
    

        return $data;
    }
}