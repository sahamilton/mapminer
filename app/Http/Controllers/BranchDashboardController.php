<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Address;
use App\Models\AddressBranch;
use App\Models\Branch;
use App\Models\Chart;
use App\Models\Company;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Track;

use App\Models\Note;
use App\Http\Requests\OpportunityFormRequest;
use App\Models\Opportunity;
use App\Models\Person;
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
        if (! session('manager')) {
            session(['manager'=>auth()->user()->id]);
        }

        $this->manager = $this->person
            ->where('user_id', '=', session('manager'))->first();
        
        if (session('branch')) {
            
            $branch = session('branch');

            return redirect()->route('branchdashboard.show', $branch);
        } else {
            
            $this->myBranches = $this->manager->getMyBranches();
            
            if (count($this->myBranches) > 0) {
                //$branch = array_keys($this->myBranches);

                return redirect()->route('branchdashboard.show', $this->myBranches[0]);
            } else {
                return redirect()->route('user.show', auth()->user()->id)
                    ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
            }
        }
       
    }
    public function summary()
    {

        return response()->view('dashboards.managersummary');
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
        
        $myBranches = $this->person->myBranches();

        
        if (! array_key_exists($branch->id, $myBranches)) {
             return redirect()->back()
                 ->withWarning("You are not assigned to " .$branch->branchname);
        }
        if (! session()->has('branch') or $branch->id != session('branch') ) {
            session(['branch'=>$branch->id]);
        }
       
        
        return response()->view('branches.newdashboard', compact('branch'));
    }
   
    
    /**
     * [_getDashBoardData description]
     * 
     * @return [type]  $data           [description]
     */
    private function _getDashBoardData()
    {
        $data['period'] = $this->period;

        $data['me'] = auth()->user()->person;
        // this might return branch managers with no branches!
        
        $data['team'] = $this->branch->with('branchTeam')->whereIn('id', $this->myBranches)->first()->branchTeam;
        
        $data['branches'] = $this->getSummaryBranchData();
        
        if (! $data['teamdata'] = $this->_myTeamsData($data)) {
            
            return false;

        }
        $data['charts'] = $this->_getCharts($data);
       
        return $data;
    }
    private function _getCharts($data)
    {
        
        $charts['activitychart'] = $this->chart->getBranchActivityByDateTypeChart(
            $this->_getActivityTypeChartData()
        );
        
        $charts['pipelinechart'] = $this->_getPipeLine();

        $charts['Top25chart'] = $this->chart->getBranchChart($data, $field='top25_opportunities');
        //$charts['winratiochart'] = [];
        $charts['openleadschart'] = $this->chart->getBranchChart($data, $field='leads_count');
        $charts['newleadschart'] = $this->chart->getBranchChart($data, $field='newbranchleads');
        $charts['activeleadschart'] = $this->chart->getBranchChart($data, $field='active_leads');
        $charts['personactivitytypechart'] = $this->chart->getTeamActivityByTypeChart($data);
        return $charts;
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
            
              return $this->manager->myBranches($this->manager);
        }
    }
    
    /**
     * [_myTeamsData return array of team & manager with stats
     * 
     * @param Collection $branchdata with branchname, id & stats
     * 
     * @return [type]                 [description]
     */
    private function _myTeamsData($data)
    {
        
        $fields = array_merge($this->leadFields, $this->opportunityFields);
        
        //* gets the associated branch data (opportunities and leads)
        foreach ($data['team'] as $report) {
            
            $mgrBranches = $report->getMyBranches();

            foreach ($fields as $field) {
                $data['teamdata'][$report->fullName()][$field] = $data['branches']
                ->whereIn('id', $mgrBranches)
                ->sum($field);

            }
        }
        //* gets the associated people data (activities)
        //$this->reports = $data['me']->getDescendantsAndSelf()->pluck('user_id')->toArray();
        $this->reports= $data['team']->pluck('user_id')->toArray();
        $data['activities'] = $this->getSummaryTeamData($this->period, $this->activityFields);
        
        //$directReports = $data['me']->descendantsAndSelf()->limitDepth(1)->get();
        foreach ($data['team'] as $report) {
          
            foreach ($this->activityFields as $field) {
               
                $data['teamdata'][$report->fullName()][$field] = $data['activities']
                    ->where('id', $report->id)
                    ->sum($field);
            }
        }

        return collect($data['teamdata']);
       
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
     *     * @return [type]           [description]
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
