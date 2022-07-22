<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;   
use \Fractal;
use App\Transformers\EventTransformer;
use App\Activity;
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
        @ray(request()->all());
        $filters = ['status'=>request('status'), 'type'=>request('type')];
        $branch = request('branch');
        if (request()->has('start') && request()->has('end')) {
            $period['from'] = Carbon::parse(request('start'));
            $period['to'] = Carbon::parse(request('end'));
        } else {
            $period = $this->activity->getPeriod();
            $period['from'] = $period['from']->startOfWeek();
            $period['to'] = $period['to']->endOfWeek();
        }
        
        return $this->_getEventsToJson($period, $branch, $filters);
       
         
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
    private function _getEventsToJson(Array $period, int $branch, $filters=null)
    {
       
        $activities = Activity::with('relatesToAddress', 'type')
            ->when(
                $filters['type']!=0, function ($q) use ($filters) {
                    $q->where('activitytype_id', $filters['type']);

                }
            )
            ->when(
                $filters['status'] !=0, function ($q) use ($filters) {
                    $q->when(
                        $filters['status'] == 2, function ($q) use ($filters) {
                            $q->whereNull('completed');
                        }, function ($q) {
                            $q->whereNotNull('completed');
                        }
                    );
                }
            ) 
            ->whereBetween('activity_date', [$period['from'], $period['to']])
            ->where('branch_id', $branch)
            ->get();
        $activities =  \Fractal::create()->collection($activities)->transformWith(EventTransformer::class)->toArray();
        @ray($activities['data']);
        return $activities['data'];
    }
}