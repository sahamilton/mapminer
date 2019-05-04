<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityType;
use App\Address;
use App\AddressBranch;
use App\Branch;
use App\Company;
use App\Track;
use App\Contact;
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
    public $contact;
    public $manager;
    public $myBranches;
    public $opportunity;
    public $period = [];
    public $person;
    public $track;

    public $keys = [];


    public function __construct(
            Activity $activity,
            Address $address,
            AddressBranch $addressbranch,
            Branch $branch,
            Contact $contact,
            Opportunity $opportunity,
            Person $person,
            Track $track
        ) {
            $this->address = $address;
            $this->addressbranch = $addressbranch;
            $this->branch = $branch;
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
     
      if(! $this->period){
        $this->period = $this->activity->getPeriod();
      }
      $this->manager = $this->person->where('user_id','=',auth()->user()->id)->first();
      $this->myBranches = $this->getBranches();
     
      if(count($this->myBranches)>0){
          $branch = array_keys($this->myBranches);
          return redirect()->route('dashboard.show',$branch[0]);
       }else{
          return redirect()->route('user.show',auth()->user()->id)
                ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }
        
       

    }

    public function setPeriod(Request $request)
    {
      
      $this->period = $this->activity->setPeriod(request('period'));
    

      return redirect()->back();
    }
    /**
     * [selectBranch description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function selectBranch(Request $request)
    {
      
     return redirect()->route('branchdashboard.show',request('branch'));

    }
    /**
     * [show description]
     * @param  [type] $branch [description]
     * @return [type]         [description]
     */
    public function show($branch)
    {
      
      
      $this->period = $this->activity->getPeriod();
   
    
      $branch = $this->branch->with('manager')->findOrFail($branch);

      if($branch->manager->count()>1 && 
        $branch->manager->where('user_id','=',auth()->user()->id)->count()==1){
          $this->manager = $branch->manager->where('user_id','=',auth()->user()->id)->first();
      }else{
        $this->manager = $branch->manager->first();
      }
      if(! $this->manager){
        return redirect()->route('dashboard.index')->withWarning("There is no manager assigned to branch ". $branch->branchname . ". Notify Sales Opersations");
      }
      $this->myBranches = [$branch->id];
      $data = $this->getDashBoardData();
   
     
      return response()->view('branches.dashboard', compact('data','branch'));

    }
   
    
    /**
     * [getDashBoardData description]
     * @param  array  $myBranches [description]
     * @return [type]             [description]
     */
    private function getDashBoardData()
    {
      $teamroles = [9]; // only branch managers
      $data['team']['me'] = $this->person->findOrFail($this->manager->id);
      // this might return branch managers with no branches!
      $data['team']['team'] =  $this->person
      ->where('reports_to','=',$this->manager->id) 
      ->WithRoles($teamroles)     
      ->get();
      //$data['team']= $this->myTeamsOpportunities();
      $data['summary'] = $this->getSummaryBranchData();

      //$data['upcoming'] = $this->getUpcomingActivities();       
      //$data['funnel'] = $this->getBranchFunnel();    
      $data['activitychart'] =  $this->getActivityChartData();
      $data['pipelinechart'] = $this->getPipeline();
    
      $data['calendar'] = $this->getUpcomingCalendar($this->getActivities());
 
      $data['period'] = $this->period;
      $branches = $this->getBranches();
      if(count($branches)>1){
        $data['branches'] = $branches;
      }
 
      return $data;
    }

    
    /**
     * [getBranches description]
     * @return [type] [description]
     */
    private function getBranches()
    {
      
      if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')){
       
            return $this->branch->all()->pluck('branchname','id')->toArray();
        
        } else {
      
             return  $this->person->myBranches();
        }
    }
    

    /**
     * [getSummaryBranchData description]
     * @param  array  $branches [description]
     * @return [type]           [description]
     */
    private function getSummaryBranchData(){
        
        return $this->branch
              ->withCount(       
                      [
                        'leads'=>function($query){
                           $query->whereBetween('address_branch.created_at',[$this->period['from'],$this->period['to']]);
                        },
                        'activities'=>function($query){
                            $query->whereBetween('activity_date',[$this->period['from'],$this->period['to']])
                            ->where('completed','=',1);
                        },

                        'opportunities',
                          'opportunities as won'=>function($query){
                  
                          $query->whereClosed(1)
                          ->whereBetween('actual_close',[$this->period['from'],$this->period['to']]);
                      },
                      'opportunities as lost'=>function($query){
                          $query->whereClosed(2)
                          ->whereBetween('actual_close',[$this->period['from'],$this->period['to']]);
                      },
                      'opportunities as top50'=>function($query){
                          $query->where('opportunities.top50','=',1)
                          ->where(function($q){
                            $q->where('actual_close','>',$this->period['to'])
                            ->orwhereNull('actual_close');
                          })
                          ->where('opportunities.created_at','<=',$this->period['to']);
                      }]
                  )
          
              ->with('manager','manager.reportsTo')
              ->getActivitiesByType($this->period)
              ->whereIn('id',$this->myBranches)
              ->get(); 

    }
  

     /**
      * [prepChartData description]
      * @param  [type] $results [description]
      * @return [type]          [description]
      */
      private function prepChartData($results){


        $string = '';

        foreach ($results as $branch){

          $string = $string . "[\"".$branch->branchname ."\",  ".$branch->activities->count() .",  ".$branch->won.", ".$branch->opportunities->sum('value') ."],";
         
        }

        return $string;

      }
      /**
       * [getUpcomingCalendar description]
       * @param  [type] $activities [description]
       * @return [type]             [description]
       */
      private function getUpcomingCalendar($activities)
      {
       
          return \Calendar::addEvents($activities);
      }    
    /**
       * [getActivities description]
       * @param  Array  $myBranches [description]
       * @return [type]             [description]
       */
      private function getActivities()
      {
             
             return $this->activity
            
             ->whereIn('branch_id',$this->myBranches)
             ->get();

      }
      /**
       * [getUpcomingActivities description]
       * @param  Array  $myBranches [description]
       * @return [type]             [description]
       */
      private function getUpcomingActivities()
      {
         return $this->activity
          ->whereNull('completed')
          ->whereIn('branch_id',$this->myBranches)
          ->get();
      }
     
      /**
       * [getActivityChartData description]
       * @param  Array  $branches [description]
       * @return [type]           [description]
       */
      private function getActivityChartData()
      {

      $branchdata = $this->getBranchActivities($this->myBranches)->toArray();
      if(stripos($this->period['period'],'week')){
        $chart = $this->formatBranchDayActivities($branchdata);
      }else{
        $chart = $this->formatBranchWeekActivities($branchdata);
      }
      
       $data['keys']= "'". implode("','",array_keys($chart['values']))."'";
       $data['data']= implode(",",$chart['values']);
       return $data;
       
      }
    /**
     * [formatBranchActivities description]
     * @param  [type] $branchdata [description]
     * @return [type]             [description]
     */
    private function formatBranchActivities($branchdata)
      { 
        $data[]=array();
        foreach($branchdata as $branch){
         
          $data[$branch['id']] = $branch['activities_count'];
        }
        if(count($data[0])>0){
          return $this->formatChartFullData($data,array_keys($data));
        }
        
        return false;
       
     }
      // reformat branch data into array
    
         /**
     * [formatBranchWeekActivities description]
     * @param  [type] $branchdata [description]
     * @return [type]             [description]
     */
      private function formatBranchDayActivities($branchdata)
      { 

      $branches = [];
      $keys =  $this->daysBetween(); 
     //dd($keys);
      foreach($branchdata as $branch){

          $branch_id = implode(",",array_keys($branch));
          foreach ($branch[implode(",",array_keys($branch))] as $item){

            foreach($item as $period=>$el){
                      
              $branches[$period]= $el->count();
              
            }
          }
         
        
     
      }

      ksort($branches);
      return $this->formatActivityTableData($branches,$keys);
       

     }
    /**
     * [formatBranchWeekActivities description]
     * @param  [type] $branchdata [description]
     * @return [type]             [description]
     */
      private function formatBranchWeekActivities($branchdata)
      { 

      $branches = [];
      $keys =  $this->yearWeekBetween(); 
     
      foreach($branchdata as $branch){

          $branch_id = implode(",",array_keys($branch));
          foreach ($branch[implode(",",array_keys($branch))] as $item){

            foreach($item as $period=>$el){
              //convert YW to weekday begining
              list ( $year,$week) = explode('-', $period);
             
              $d = new Carbon;
              $d->setISODate($year, $week);
                           
              $branches[$d->endOfWeek()->format('Y-m-d')]= $el->count();
              
            }
          }
         
        
     
      }
      ksort($branches);
    
      return $this->formatActivityTableData($branches,$keys);
       

     }
     
     /**
      * [fillMissingPeriods description]
      * @param  [type] $branches [description]
      * @param  Carbon $from     [description]
      * @param  Carbon $to       [description]
      * @return [type]           [description]
      */
     private function fillMissingPeriods($branches,Carbon $from=null,Carbon $to=null)
     {
       
       if(! $from){
          $from = clone($this->period['from']);
        }
        if(! $to){
          $to = clone($this->period['to']);
        }
        $keys = $this->yearWeekBetween($from,$to);
       
        for($i = $from->format('Y-m-d'); $from <= $to->format('Y-m-d');$from->addWeeks(1)){
           
              if(! in_array($i,$keys)){
                  $keys[]=$i;
              }
            
              if(! array_key_exists($i,$branches)) {
                 
                  $branches[$i] = 0;
              }
         
          }
          ksort($branches);
          return $branches;

     }
     
    /**
     * [formatActivityTableData description]
     * @param  array  $branches [description]
     * @param  array  $keys     [description]
     * @return [type]           [description]
     */
     private function formatActivityTableData(array $branches,array $keys)
     {
    
     foreach ($keys as $key){
      
      if(array_key_exists($key, $branches)){
        $data['values'][$key] = $branches[$key];
      }else{
        $data['values'][$key] =0;
      }
     }
     
      $data['branches'] = $branches;
      $data['keys'] = $keys;

      return $data;
     

     }
     /**
      * [formatChartFullData description]
      * @param  Array  $branches [description]
      * @param  array  $keys     [description]
      * @return [type]           [description]
      */
     private function formatChartFullData(Array $branches,array $keys)
     {
        $colors = $this->activity->createColors(count($branches));
        $data = [];
        $chartdata = '';
        $i = 0;

        foreach ($branches as $branch=>$info){
           
            $chartdata = $chartdata . "{
                label: \"Branch " .$branch ."\",
            backgroundColor:'".$colors[$i] . "',
            data: [".$info."]},";
            
            $i++;
        }
        $data['keys'] = null;
      
        $data['chartdata'] = str_replace("\r\n","",$chartdata);
        return $data;
     }


     /**
      * [formatChartData description]
      * @param  Array  $branches [description]
      * @param  array  $keys     [description]
      * @return [type]           [description]
      */
     private function formatChartData(Array $branches,array $keys){

        $colors = $this->activity->createColors(count($branches));
        $data = [];
        $chartdata = '';
        $i = 0;

        foreach ($branches as $branch=>$info){
           
            $chartdata = $chartdata . "{
                label: \"Branch " .$branch ."\",
            backgroundColor:'".$colors[$i] . "',
            data: [".implode(",",$info)."]},";
            
            $i++;
        }

        $data['keys'] = implode(",",$keys);
      
        $data['data'] = str_replace("\r\n","",$chartdata);
       
       return $data;


     }

     /**
      * [Return 2 months worth of activities groupes by branch & week]
      * @param  array  $myBranches [description]
      * @return [type]             [description]
      */
     private function getBranchActivities()
     {
      
        $activityCount = $this->branch->whereIn('id',$this->myBranches)
         ->whereHas('activities',function ($q){
            $q->whereBetween('activity_date',[$this->period['from'],$this->period['to']])
            ->where('completed','=',1);
          })
         ->with(['activities'=>function ($q){
            $q->whereBetween('activity_date',[$this->period['from'],$this->period['to']])
            ->where('completed','=',1);
         }])->get();
         
     
        // if this period is week then group by day
         return $activityCount->map(function ($branch){
            return [$branch->id=>[$branch->activities->groupBy(function ($activity) {
              if(stripos($this->period['period'],'week')){
                
                return $activity
                 ->activity_date->format('Y-m-d');
              }
                 return $activity
                 ->activity_date->endOfWeek()
                 ->format('Y-W');
            })]];
           });

     }
     /**
      * [get 2 months of won opportunities description]
      * @param  array  $myBranches [description]
      * @return [type]             [description]
      */
    private function getWonOpportunities(){
    
      $won =  $this->opportunity
                ->selectRaw('branch_id,YEARWEEK(actual_close,3) as yearweek,sum(value) as total')
                ->whereNotNull('value')
                ->where('value','>',0)
                ->whereIn('branch_id',$this->myBranches)
                ->whereBetween('actual_close',[$this->period['from'],$this->period['to']])
                ->whereClosed(1)
                ->groupBy(['branch_id','yearweek'])
                ->orderBy('branch_id','asc')
                ->orderBy('yearweek','asc')
                ->get();
      $data = [];
      
      foreach ($won as $item){
        
          $data[$item->branch_id][$item->yearweek]=$item->total;
          
      }

      $keys =  $this->yearWeekBetween($this->period['from'], $this->period['to']);
      $wondata = [];
      foreach(array_unique($won->pluck('branch_id')->toArray()) as $branch_id){
        

        $wondata[$branch_id]= $this->fillMissingPeriods($data[$branch_id]);
      
      }
      
      return $this->formatChartData($wondata,$keys);
    }

    /*
     Return 2 months of won opportunities
     */
    /**
     * Get upcoming opportunity closes
     * @param  array  $myBranches [description]
     * @return [type]             [description]
     */
    private function getPipeline(){
        
        $pipeline = $this->getPipeLineData();
     
        return $this->formatPipelineData($pipeline);
     }
     /**
      * [getPipeLineData description]
      * @param  array  $myBranches [description]
      * @return [type]             [description]
      */
     private function getPipeLineData()
     {

     
     return $this->opportunity
                    ->selectRaw('branch_id,YEARWEEK(expected_close,3) as yearweek,sum(value) as total')
                    ->where('value','>',0)
                    ->whereIn('branch_id',$this->myBranches)
                    
                    ->where(function($q){
                      $q->where('actual_close','>',$this->period['to'])
                      ->orWhereNull('actual_close');
                    })
                    ->groupBy(['branch_id','yearweek'])
                    ->orderBy('branch_id','asc')
                    ->orderBy('yearweek','asc')
                    ->get();
    }

    /**
     * [formatPipelineData description]
     * @param  [type] $pipeline [description]
     * @return [type]           [description]
     */
    private function formatPipelineData($pipeline)
    {
     
      $chartdata = [];
     
      foreach ($pipeline as $item){
        
          $chartdata[$item->yearweek]=$item->total;
          
      }
    
      $from = Carbon::now();
      $to = Carbon::now()->addMonth(2);
      $keys =  $this->yearWeekBetween($from, $to);
     
      $data['keys'] = "'".implode("','",$keys)."'";
      $data['data'] = implode(",",$chartdata); 
      return $data;
     
    }
    /**
     * [daysBetween description]
     * @param  [type] $from [description]
     * @param  [type] $to   [description]
     * @return [type]       [description]
     */
    private function daysBetween(Carbon $from=null,Carbon $to=null)
    {

      if(! $from){
        $from = clone($this->period['from']);
      }
      if(! $to){
        $to = clone($this->period['to']);
      }
      $keys=[];
      for($i = $from->format('Y-m-d'); $i<= $to->format('Y-m-d');$i = $from->addDay()->format('Y-m-d')){
        $keys[]=$i;
      }

      return $keys;
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
      for($i = $from->endOfWeek()->format('Y-m-d'); $i<= $to->endOfWeek()->format('Y-m-d');$i = $from->addWeek()->format('Y-m-d')){
        $keys[]=$i;
      }

      return $keys;
    }
}

