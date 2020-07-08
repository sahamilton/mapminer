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
        
        if (session()->has('impersonated_by')) {
            session()->forget('branch');
        }
       
        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }

        $this->manager = $this->person
            ->where('user_id', '=', auth()->user()->id)->first();
        
        if (session('branch')) {
            
            $branch = session('branch');

            return redirect()->route('branchdashboard.show', $branch);
        } else {
           
            $this->myBranches = $this->_getBranches();
            
            if (count($this->myBranches) > 0) {
                $branch = array_keys($this->myBranches);

                return redirect()->route('branchdashboard.show', $branch[0]);
            } else {
                return redirect()->route('user.show', auth()->user()->id)
                    ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
            }
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
        
        $this->period = $this->activity->setPeriod($request);
    

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
    public function show(Branch $branch)
    {
       
        if (! session()->has('branch') or $branch->id != session('branch') ) {
            session(['branch'=>$branch->id]);
        }
        
        $this->period = $this->activity->getPeriod();

        $branch->load('manager');

        if ($branch->manager->count() > 1 
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
        $data['summary'] = $this->getSummaryBranchData();
   
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
        
        if (auth()->user()->hasRole('admin')) {
       
            return $this->branch->all()->pluck('branchname', 'id')->toArray();
        } elseif (auth()->user()->hasRole('sales_operations')) {
            $manager = $this->person->findOrFail(auth()->user()->person->reports_to);
            
            return $this->person->myBranches($manager);
        } else {
            
             return  $this->person->myBranches();
        }
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
        return [];
        //return \Calendar::addEvents($activities);
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
            ->whereBetween('activity_date', [now()->subMonth(3), now()->addMonth(6)])
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
            ->typeDayCount()
            ->get();
       
    }
     
    
    
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
            ->selectRaw('branch_id,YEARWEEK(expected_close,0) as yearweek,sum(value) as total')
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
            $date = Carbon::now();
            if ($item->yearweek) {
                 $date->setISODate(substr($item->yearweek, 0, 4), substr($item->yearweek, 4, 2))->endOfWeek();
            }
           
            $chartdata[$date->format('Y-m-d')]=$item->total;
            
        }
        
        $from = Carbon::now();
        $to = Carbon::now()->addMonth(2);
        $keys =  $this->_yearWeekBetween($from, $to);
        
        $data['keys'] = "'".implode("','", $keys)."'";
       
        $data['data']=[];
        
        foreach ($keys as $key) {
            if (isset($chartdata[$key])) {
                $data['data'][$key] = $chartdata[$key];
            } else {
                $data['data'][$key] = 0;
            }
            
        }
        
        $data['data'] = implode(",", $data['data']); 
    
        return $data;
     
    }

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
