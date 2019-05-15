<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityType;
use App\Address;
use App\AddressBranch;
use App\Branch;
use App\Chart;
use App\Company;
use App\Contact;
use App\Track;

use App\Note;
use App\Http\Requests\OpportunityFormRequest;
use App\Opportunity;
use App\Person;
use \Carbon\Carbon;
use Illuminate\Http\Request;

class BranchDashboardController extends DashboardController
{
    public $activity;
    public $address;
    public $addressbranch;
    public $branch;
    public $chart;
    public $contact;
    public $manager;
    public $myBranches;
    public $opportunity;
    public $period = [];
    public $person;
    public $track;

    public $keys = [];

    /**
     * [__construct description]
     * 
     * @param Activity      $activity      [description]
     * @param Address       $address       [description]
     * @param AddressBranch $addressbranch [description]
     * @param Branch        $branch        [description]
     * @param Chart         $chart         [description]
     * @param Contact       $contact       [description]
     * @param Opportunity   $opportunity   [description]
     * @param Person        $person        [description]
     * @param Track         $track         [description]
     */
    public function __construct(
        Activity $activity,
        Address $address,
        AddressBranch $addressbranch,
        Branch $branch,
        Chart $chart,
        Contact $contact,
        Opportunity $opportunity,
        Person $person,
        Track $track
    ) {
            $this->address = $address;
            $this->addressbranch = $addressbranch;
            $this->branch = $branch;
            $this->chart = $chart;
            $this->contact = $contact;
            $this->opportunity = $opportunity;
            $this->person = $person;
            $this->activity = $activity;
            $this->track = $track;    
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }
        $this->manager = $this->person
            ->where('user_id', '=', auth()->user()->id)->first();
        $this->myBranches = $this->_getBranches();
     
        if (count($this->myBranches)>0) {
            $branch = array_keys($this->myBranches);
            return redirect()->route('dashboard.show', $branch[0]);
        } else {
            return redirect()->route('user.show', auth()->user()->id)
                ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }
          
       

    }

    /**
     * [setPeriod description]
     * 
     * @param Request $request [description]
     * 
     * @return redirect back
     */
    public function setPeriod(Request $request)
    {
      
        $this->period = $this->activity->setPeriod(request('period'));
    

        return redirect()->back();
    }
    /**
     * [selectBranch description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function selectBranch(Request $request)
    {
      
        return redirect()->route('branchdashboard.show', request('branch'));

    }
    /**
     * [show description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function show($branch)
    {
      
      
        $this->period = $this->activity->getPeriod();

        $branch = $this->branch->with('manager')->findOrFail($branch);

        if ($branch->manager->count()>1 
            && $branch->manager->where(
                'user_id', '=', auth()->user()->id
            )->count()==1 
        ) {
            $this->manager = $branch->manager->where(
                'user_id', '=', auth()->user()->id
            )->first();
        } else {
            $this->manager = $branch->manager->first();
        }
        if (! $this->manager) {
            return redirect()->route('dashboard.index')
                ->withWarning(
                    "There is no manager assigned to branch "
                    . $branch->branchname 
                    . ". Notify Sales Opersations"
                );
        }
        $this->myBranches = [$branch->id];
        $data = $this->_getDashBoardData();
     
     
        return response()->view('branches.dashboard', compact('data', 'branch'));

    }
   
    
    /**
     * [_getDashBoardData description]
     * 
     * @return [type]  $data           [description]
     */
    private function _getDashBoardData()
    {
        $teamroles = [9]; // only branch managers
        $data['team']['me'] = $this->person->findOrFail($this->manager->id);
        // this might return branch managers with no branches!
            
        $data['team']['team'] =  $this->person
            ->where('reports_to', $this->manager->id) 
            ->WithRoles($teamroles)     
            ->get();
          //$data['team']= $this->myTeamsOpportunities();
        $data['summary'] = $this->_getSummaryBranchData();
    
        //$data['activitychart'] =  $this->_getActivityChartData();
        $data['activitychart'] = $this->chart->getBranchActivityByTypeChart(
            $this->_getActivityTypeChartData()
        );
       
        $data['pipelinechart'] = $this->_getPipeLine();

        $data['calendar'] = $this->_getUpcomingCalendar($this->_getActivities());

        $data['period'] = $this->period;
        $branches = $this->_getBranches();
        if (count($branches) > 1) {
            $data['branches'] = $branches;
        }
    
        return $data;
    }

    
    /**
     * [getBranches description]
     * 
     * @return [type] [description]
     */
    private function _getBranches()
    {
      
        if (auth()->user()->hasRole('admin') 
            or auth()->user()->hasRole('sales_operations')
        ) {
       
            return $this->branch->all()->pluck('branchname', 'id')->toArray();
        
        } else {
      
             return  $this->person->myBranches();
        }
    }
    

    /**
     * [_getSummaryBranchData description]
     * 
     * @return [type]           [description]
     */
    private function _getSummaryBranchData()
    {
        
        return $this->branch
            ->summaryStats($this->period)
            ->with('manager', 'manager.reportsTo')
            ->getActivitiesByType($this->period)
            ->whereIn('id', $this->myBranches)
            ->get();

    }
  

    /**
     * [_getUpcomingCalendar description]
     * 
     * @param [type] $activities [description]
     * 
     * @return [type]             [description]
     */
    private function _getUpcomingCalendar($activities)
    {
       
          return \Calendar::addEvents($activities);
    }    
    /**
     * [_getActivities description]
     * 
     * @return [type]             [description]
     */
    private function _getActivities()
    {
             
        return $this->activity
            ->with('relatesToAddress')
            ->whereIn('branch_id', $this->myBranches)
            ->get();

    }
    /**
     * [_getActivityTypeChartData description]
     * 
     * @return [type]           [description]
     */
    private function _getActivityTypeChartData()
    {

        return $this->activity->whereIn('branch_id', $this->myBranches)
            ->periodActivities($this->period)
            ->completed()
            ->typeCount()
            ->get();
       
    }
     
    /**
     * [_getActivityChartData description]
     * 
     * @return [type]           [description]
     */
    /*private function _getActivityChartData()
    {

        $branchdata = $this->_getBranchActivities($this->myBranches)->toArray();
        if (stripos($this->period['period'], 'week')) {
            $chart = $this->_formatBranchDayActivities($branchdata);
        } else {
            $chart = $this->_formatBranchWeekActivities($branchdata);
        }
      
        $data['keys']= "'". implode("','", array_keys($chart['values']))."'";
        $data['data']= implode(",", $chart['values']);
        return $data;
       
    }*/

    
    /**
     * [_formatBranchWeekActivities description]
     * 
     * @param [type] $branchdata [description]
     * 
     * @return [type]             [description]
     */
    /*private function _formatBranchDayActivities($branchdata)
    { 

        $branches = [];
        $keys =  $this->_daysBetween(); 

        foreach ($branchdata as $branch) {

            $branch_id = implode(",", array_keys($branch));
            foreach ($branch[implode(",", array_keys($branch))] as $item) {

                foreach ($item as $period=>$el) {
              
                    $branches[$period]= $el->count();

                }
            }
  
        }

        ksort($branches);
        return $this->_formatActivityTableData($branches, $keys);
    }*/
    
    /**
     * [_formatBranchWeekActivities description]
     * 
     * @param [type] $branchdata [description]
     * 
     * @return [type]             [description]
     */
    /*private function _formatBranchWeekActivities($branchdata)
    { 

        $branches = [];
        $keys =  $this->_yearWeekBetween(); 
       
        foreach ($branchdata as $branch) {

            $branch_id = implode(",", array_keys($branch));
            
            foreach ($branch[implode(",", array_keys($branch))] as $item) {

                foreach ($item as $period=>$el) {
                
                    list ( $year, $week) = explode('-', $period);

                    $d = new Carbon;
                    $d->setISODate($year, $week);
                           
                    $branches[$d->endOfWeek()->format('Y-m-d')]= $el->count();

                }
            }

        }
        ksort($branches);
    
        return $this->_formatActivityTableData($branches, $keys);
  
    }*/
     
     /**
      * [_fillMissingPeriods description]
      * 
      * @param [type] $branches [description]
      * @param Carbon $from     [description]
      * @param Carbon $to       [description]
      * 
      * @return [type]           [description]
      */
    /*private function _fillMissingPeriods($branches, Carbon $from,Carbon $to)
    { 
        if (! $from) {
            $from = clone($this->period['from']);
        }
        if (! $to) {
            $to = clone($this->period['to']);
        }
        $keys = $this->_yearWeekBetween($from, $to);
       
        for ($i = $from->format('Y-m-d'); 
            $from <= $to->format('Y-m-d');
            $from->addWeeks(1)) {
           
            if (! in_array($i, $keys)) { 
                $keys[]=$i;
            }
          
            if (! array_key_exists($i, $branches)) {
               
                $branches[$i] = 0;
            }
         
        }
        ksort($branches);
        return $branches;

    }*/
     
    /**
     * [_formatActivityTableData description]
     * 
     * @param array $branches [description]
     * @param array $keys [description]
     * 
     * @return [type]           [description]
     */
    /*private function _formatActivityTableData($branches,$keys)
    {
        foreach ($keys as $key) {
      
            if (array_key_exists($key, $branches)) {
                $data['values'][$key] = $branches[$key];
            } else {
                $data['values'][$key] =0;
            }
        }

        $data['branches'] = $branches;
        $data['keys'] = $keys;

        return $data;
 

    }*/

    /**
     * [_formatChartFullData description]
     * 
     * @param Array $branches [description]
     * @param Array $keys     [description]
     * 
     * @return [type]           [description]
     */
    /*private function _formatChartFullData(Array $branches, array $keys)
    {
        $colors = $this->activity->createColors(count($branches));
        $data = [];
        $chartdata = '';
        $i = 0;

        foreach ($branches as $branch=>$info) {
           
            $chartdata = $chartdata . "{
                label: \"Branch " .$branch ."\",
            backgroundColor:'".$colors[$i] . "',
            data: [".$info."]},";
            
            $i++;
        }
        $data['keys'] = null;
        $data['chartdata'] = str_replace("\r\n", "", $chartdata);
        return $data;
    }*/


     /**
      * [_formatChartData description]
      * 
      * @param Array $branches [description]
      * @param array $keys     [description]
      * 
      * @return [type]           [description]
      */
    /*private function _formatChartData(Array $branches,array $keys) 
    {

        $colors = $this->activity->createColors(count($branches));
        $data = [];
        $chartdata = '';
        $i = 0;

        foreach ($branches as $branch=>$info) {
           
            $chartdata = $chartdata . "{
                label: \"Branch " .$branch ."\",
            backgroundColor:'".$colors[$i] . "',
            data: [".implode(",", $info)."]},";
            
            $i++;
        }

        $data['keys'] = implode(",", $keys);
      
        $data['data'] = str_replace("\r\n", "", $chartdata);
       
        return $data;


    }*/

     /**
      * [Return 2 months worth of activities groupes by branch & week]
      * 
      * @return [type]             [description]
      */
    /*private function _getBranchActivities()
    {
      
        $activityCount = $this->branch->whereIn('id', $this->myBranches)
            ->with(
                ['activities'=>function ($q) {
                        $q->period($this->period)->completed();
                }]
            )
        ->get();

        // if this period is week then group by day
        return $activityCount->map(
            function ($branch) {
                return [$branch->id=>[$branch->activities->groupBy(
                    function ($activity) {
                        if (stripos($this->period['period'], 'week')) {
                            return $activity->activity_date->format('Y-m-d');
                        }
                        return $activity
                            ->activity_date->endOfWeek()
                            ->format('Y-W');
                    }
                )]];
            }
        );

    }*/
    
    /**
     * [_getPipeLine description]
     * 
     * @return [type] [description]
     */
    private function _getPipeLine()
    {
        
        $pipeline = $this->_getPipeLineData();
     
        return $this->_formatPipelineData($pipeline);
    }
     /**
      * [getPipeLineData description]
      * 
      * @return [type]             [description]
      */
    private function _getPipeLineData()
    {

     
        return $this->opportunity
            ->selectRaw('branch_id,YEARWEEK(expected_close,3) as yearweek,sum(value) as total')
            ->where('value', '>', 0)
            ->whereIn('branch_id', $this->myBranches)
            
            ->where(
                function ($q) {
                    $q->where('actual_close', '>', $this->period['to'])
                        ->orWhereNull('actual_close');
                }
            )
            ->groupBy(['branch_id','yearweek'])
            ->orderBy('branch_id', 'asc')
            ->orderBy('yearweek', 'asc')
            ->get();
    }

    /**
     * [_formatPipelineData description]
     * 
     * @param [type] $pipeline [description]
     * 
     * @return [type]           [description]
     */
    private function _formatPipelineData($pipeline)
    {
     
        $chartdata = [];
     
        foreach ($pipeline as $item) {
          
            $chartdata[$item->yearweek]=$item->total;
            
        }
      
        $from = Carbon::now();
        $to = Carbon::now()->addMonth(2);
        $keys =  $this->_yearWeekBetween($from, $to);
       
        $data['keys'] = "'".implode("','", $keys)."'";
        $data['data'] = implode(",", $chartdata); 
        return $data;
     
    }
    /**
     * [daysBetween description]
     * 
     * @param [type] $from [description]
     * @param [type] $to   [description]
     * 
     * @return [type]       [description]
     */
    /*private function _daysBetween(Carbon $from = null,Carbon $to = null)
    {

        if (! $from) {
            $from = clone($this->period['from']);
        }
        if (! $to) {
            $to = clone($this->period['to']);
        }
        $keys=[];
        for ($i = $from->format('Y-m-d'); 
            $i<= $to->format('Y-m-d');
            $i = $from->addDay()->format('Y-m-d')) {
            $keys[]=$i;
        }

        return $keys;
    }*/
    /**
     * [yearWeekBetween description]
     * 
     * @param [type] $from [description]
     * @param [type] $to   [description]
     * 
     * @return [type]       [description]
     */
    private function _yearWeekBetween(Carbon $from=null,Carbon $to=null)
    {

        if (! $from) {
            $from = clone($this->period['from']);
        }
        if (! $to) {
            $to = clone($this->period['to']);
        }
        
        

        $keys=[];
        for ($i = $from->endOfWeek()->format('Y-m-d'); 
            $i<= $to->endOfWeek()->format('Y-m-d');
            $i = $from->addWeek()->format('Y-m-d')) {
            $keys[]=$i;
        }

        return $keys;
    }
}
