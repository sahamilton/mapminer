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

class BranchDashboardController extends Controller
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
        
       //$data = $this->getDashBoardData(array_keys($this->myBranches));
       
       //$data['period'] = $this->period;
     
       //return response()->view('opportunities.mgrindex', compact('data'));

    }

    public function setPeriod(Request $request)
    {
      
      $this->period = $this->activity->setPeriod(request('period'));
    

      return redirect()->route('dashboard.index');
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
       
      if(! $this->period){
        $this->period = $this->activity->getPeriod();
      }
   
      $branch = $this->branch->with('manager')->findOrFail($branch);

      $this->manager = $branch->manager->first();
      if(!$this->manager){
        return redirect()->route('dashboard.index')->withMessage("There is no manager assigned to branch ". $branch->branchname . ". Notify Sales Opersations");
      }
      $this->myBranches = [$branch->id];
      $data = $this->getDashBoardData();
      
     
      return response()->view('branches.dashboard', compact('data','branch'));

    }
   
    /**
     * [manager description]
     * @param  Request     $request [description]
     * @param  Person|null $manager [description]
     * @return [type]               [description]
     */
    public function manager(Request $request, Person $manager=null)
    {
   
      if(! $this->period){
        $this->period = $this->activity->getPeriod();
      }

      if($manager){
        $myteam = $this->person->myTeam()->pluck('id')->toArray();
        if(! in_array($manager->id, $myteam)){
          return redirect()->back()->withError('That is not one of your team members');
        }
        $this->manager = $manager;
      }else{
        
        $this->manager = $this->person->findOrFail(request('manager'));
      }
      
      $team = $this->manager->descendantsAndSelf()
              ->with('branchesServiced')->get();

      $branches = $team->map(function ($mgr){
        return $mgr->branchesServiced->pluck('id')->toArray();
      }); 
      if(count($branches->first())==0){
        return redirect()->back()->withMessage($this->manager->fullName().' is not assigned to any branches');
      }
     
      $myBranches = array_unique($branches->flatten()->toArray());

      $data = $this->getDashBoardData($myBranches);
      dd('175',$data);
      return response()->view('branches.dashboard', compact('data'));
    }
    
    /**
     * [getDashBoardData description]
     * @param  array  $myBranches [description]
     * @return [type]             [description]
     */
    private function getDashBoardData()
    {
      
      $data['team']['me'] = $this->person->findOrFail($this->manager->id);
      // this might return branch managers with no branches!
      $data['team']['team'] =  $this->person
      ->where('reports_to','=',$this->manager->id)      
      ->get();
      //$data['team']= $this->myTeamsOpportunities();
      $data['summary'] = $this->getSummaryBranchData();

      //$data['upcoming'] = $this->getUpcomingActivities();       
      //$data['funnel'] = $this->getBranchFunnel();    
      $data['activitychart'] =  $this->getActivityChartData();  
      $data['pipelinechart'] = $this->getPipeline();
    
      $data['calendar'] = $this->getUpcomingCalendar($this->getUpcomingActivities());
      //$data['chart'] = $this->getChartData();
      //$data['won'] = $this->getWonOpportunities(); 
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
     * [getBranchFunnel description]
     * @param  array  $branches [description]
     * @return [type]           [description]
     */
    /*private function getBranchFunnel(){
    
         return $this->opportunity
                     ->whereHas('branch',function ($q) {
                        $q->whereIn('branch_id',$this->myBranches);
                     })
                     ->whereNotNull('expected_close')
                     ->openFunnel()->get(); 
    }*/
    /**
     * myTeamsOpportunities Extract and sum all the associated
     * branches statistics
     * @return [associative array] 
     */
    /*private function myTeamsOpportunities()
    {
      $stats = ['leads',
              'opportunities',
              'top50',
              'booked',
              'won',
              'lost',
              'pipeline',
              'activities'];

      $data['me'] = $this->person->findOrFail($this->manager->id);
      // this might return branch managers with no branches!
      $data['team'] =  $this->person
      ->where('reports_to','=',$this->manager->id)      
      ->get();

      // get all branch managers
      $branchManagerRole = 9;
      foreach ($data['team'] as $team){
        $data['branchteam'] = $team->descendantsAndSelf()->withRoles([$branchManagerRole])
            ->has('branchesServiced')
            ->with('branchesServiced')
            ->get();
        if($data['branchteam']->count()>0){

          $sum = $data['branchteam']->map(function ($manager){

          return [$manager->id=>$manager->branchesServiced->map(function ($branch){

            return [
              'leads'=>$branch->leads
                  ->whereBetween('address_branch.created_at',[$this->period['from'],$this->period['to']])
                  ->count(),
              'opportunities'=>$branch->opportunities
             
                ->where('opportunities.created_at','<=',$this->period['to'])
                ->count(),
              'top50'=>$branch->opportunities
                ->where('opportunities.created_at','<=',$this->period['to'])
              
                ->where('top50','=',1)
                
                ->count(),
              'won'=>$branch->opportunities
                ->where('closed','=',1)
                ->whereBetween('actual_close',[$this->period['from'],$this->period['to']])
                ->count(),
              'booked'=>$branch->opportunities
                  ->where('closed','=',1)
                  ->whereBetween('actual_close',[$this->period['from'],$this->period['to']])
                  ->sum('value'),
              'lost'=>$branch->opportunities->where('closed','=',2)
                  ->whereBetween('actual_close',[$this->period['from'],$this->period['to']])
                  ->count(),
              'pipeline'=>$branch->opportunities
                ->where('closed','=',0)
                ->where('created_at','<=',$this->period['to'])
                ->where('expected_close','>',$this->period['to'])
                ->sum('value'),
              'activities'=>$branch->activities
                ->whereBetween('activity_date',[$this->period['from'],$this->period['to']])
                ->count()];
              })];
            });
            // zero out the associative array
            foreach($stats as $stat){
              $data[$stat] = 0;
            }

            foreach ($sum as $manager){
              foreach ($manager as $mgrid=>$items){
                foreach ($stats as $stat){
                  $data[$stat] += $items->sum($stat);
                }
              }

            }
            $data['results'][$team->id] = $data;
            
          }else{
            foreach($stats as $stat){
              $data['results'][$team->id][$stat] = 0;
            }
            
          } 

        }
  
      $data = $this->getTeamChart($data);
      return $data;
    }
    private function getTeamChart(array $data){

      $data['chart'] = $this->getTeamActivityChart($data);
      $data['pipelinechart'] = $this->getTeamPipelineChart($data);
      $data['top50chart'] = $this->getTeamTop50Chart($data);
    
      return $data;
    }*/
    private function getTeamActivityChart(array $data)
    {
      
      $chart= array();
      foreach($data['team'] as $team){
        $chart[$team->lastname]=$data['results'][$team->id]['activities'];

      }
      $data['chart']['keys'] = "'" . implode("','",array_keys($chart))."'";
      $data['chart']['data'] = implode(",",$chart);
      
      return $data['chart'];
    }

    /*private function getTeamPipelineChart(array $data)
    {
      
      $chart= array();

      foreach($data['team'] as $team){
        
        $chart[$team->lastname]=$data['results'][$team->id]['pipeline'];
        

      }
      $data['pipelinechart']['keys'] = "'" . implode("','",array_keys($chart))."'";
      $data['pipelinechart']['data'] = implode(",",$chart);
    
      return $data['pipelinechart'];
    }*/

    /*private function getTeamTop50Chart(array $data)
    {
      
      $chart= array();
      foreach($data['team'] as $team){
        $chart[$team->lastname]=$data['results'][$team->id]['top50'];

      }
      $data['chart']['keys'] = "'" . implode("','",array_keys($chart))."'";
      $data['chart']['data'] = implode(",",$chart);
      
      return $data['chart'];
    }
*/



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
                            $query->whereBetween('activity_date',[$this->period['from'],$this->period['to']]);
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
                          $query->whereClosed(0)
                          ->where('opportunities.top50','=',1)
                          ->where(function($q){
                            $q->where('actual_close','>',$this->period['to'])
                            ->orwhereNull('actual_close');
                          })
                          ->where('opportunities.created_at','<',$this->period['to']);
                      }]
                  )
          
              ->with('manager','manager.reportsTo')
              ->getActivitiesByType($this->period)
              ->whereIn('id',$this->myBranches)
              ->get(); 

    }
   
    /**
     * [getChartData description]
     * @param  [type] $branches [description]
     * @return [type]           [description]
     */
    private function getChartData()
    {
       $results =   $this->branch
                    ->whereIn('id',$this->myBranches)
                    ->getActivitiesByType($this->period,4)

                    ->withCount(       
                       ['leads'=>function($query){
                            $query->whereBetween('address_branch.created_at',[$this->period['from'],$this->period['to']]);
                        },
                        'opportunities as won'=>function($query){
                        
                                $query->whereClosed(1)
                                ->whereBetween('actual_close',[$this->period['from'],$this->period['to']]);
                            }]
                        )
                    ->with(['opportunities'=>function ($q){
                      $q->whereClosed(1)
                      ->whereBetween('actual_close',[$this->period['from'],$this->period['to']]);
                    }])
                    ->get();
       return $this->prepChartData($results);       
       
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
       * [getUpcomingActivities description]
       * @param  Array  $myBranches [description]
       * @return [type]             [description]
       */
      private function getUpcomingActivities()
      {
             $users =  $this->person->myBranchTeam($this->myBranches);
             return $this->activity->whereIn('user_id',$users)->get();

      }
     
      /**
       * [getActivityChartData description]
       * @param  Array  $branches [description]
       * @return [type]           [description]
       */
    private function getActivityChartData()
    {

      $branchdata = $this->getBranchActivities($this->myBranches)->toArray();
      
        return $this->formatBranchWeekActivities($branchdata);
      
    }

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
    


      private function formatBranchWeekActivities($branchdata)
      { 
      $branches = [];
      $keys =  $this->yearWeekBetween(); 
   
      foreach($branchdata as $branch){

          $branch_id = implode(",",array_keys($branch));
          foreach ($branch[implode(",",array_keys($branch))] as $item){

            foreach($item as $period=>$el){
              //convert YW to wekday begign
              list ( $year,$week) = explode('-', $period);
              $d = new Carbon;
              $d->setISODate($year, $week);              
              $branches[$branch_id][$d->format('Y-m-d')]= $el->count();

            }
          }
           
          // fill any missing periods with zeros
          // 
          
          $branches[$branch_id] = $this->fillMissingPeriods($branches[$branch_id],$this->period['from'], $this->period['to']);
          // sort branch array in date sequence
        ksort($branches[$branch_id]);
     
      }

     // if too many for graph return table
    
      if(count($branches) <= 10){
        return $this->formatChartData($branches,$keys);
      }
      return $this->formatActivityTableData($branches,$keys);
       

     }
     
     /**
      * [fillMissingPeriods description]
      * @param  [type] $branches [description]
      * @param  Carbon $from     [description]
      * @param  Carbon $to       [description]
      * @return [type]           [description]
      */
     private function fillMissingPeriods($branches)
     {
       
        $from = clone($this->period['from']);
        $to = clone($this->period['to']);
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
    /* private function formatActivityTableData(array $branches,array $keys)
     {
     
      $data['branches'] = $branches;
      $data['keys'] = $keys;

      return $data;
     

     }*/
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
            $q->whereBetween('activity_date',[$this->period['from'],$this->period['to']]);
          })
         ->with(['activities'=>function ($q){
            $q->whereBetween('activity_date',[$this->period['from'],$this->period['to']]);
         }])->get();
      
         return  $activityCount->map(function ($branch){
            return [$branch->id=>[$branch->activities->groupBy(function ($activity) {
                 return $activity->activity_date->format('Y-W');
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

  
     return  $this->opportunity
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
      $data = [];
     
      foreach ($pipeline as $item){
        
          $data[$item->branch_id][$item->yearweek]=$item->total;
          
      }

      $from = Carbon::now();
      $to = Carbon::now()->addMonth(2);
      $keys =  $this->yearWeekBetween($from, $to);
              
      foreach($data as $branch_id=>$branchdata){
        
        $data[$branch_id] = $this->fillMissingPeriods($branchdata,$from, $to);
        
      }
      
      return $this->formatChartData($data,$keys);
    }
    /**
     * [yearWeekBetween description]
     * @param  [type] $from [description]
     * @param  [type] $to   [description]
     * @return [type]       [description]
     */
    private function yearWeekBetween()
    {
      
      $dates['from'] = clone($this->period['from']);
      $dates['to'] = clone($this->period['to']);

      $keys=[];
      for($i = $dates['from']->format('Y-m-d'); $i<= $dates['to']->format('Y-m-d');$i = $dates['from']->addWeek()->format('Y-m-d')){
        $keys[]="'".$i."'";
      }

      return $keys;
    }
}

