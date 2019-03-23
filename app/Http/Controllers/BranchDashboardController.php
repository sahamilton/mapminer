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
    public $opportunity;
    
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
        $this->manager = $this->person->where('user_id','=',auth()->user()->id)->first();
        $myBranches = $this->getBranches();
   
        if(count($myBranches)==1){
          $branch = array_keys($myBranches);
          return redirect()->route('dashboard.show',$branch[0]);
        }
        if(count($myBranches)==0){
                return redirect()->route('user.show',auth()->user()->id)
                ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
            }
       $this->setTimeFrame();
       $data = $this->getDashBoardData(array_keys($myBranches));
       return response()->view('opportunities.mgrindex', compact('data'));

    }
    /**
     * [selectBranch description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function selectBranch(Request $request)
    {

      $data = $this->getDashBoardData([request('branch')]);
      return $this->displayDashboard($data);

    }
    /**
     * [show description]
     * @param  [type] $branch [description]
     * @return [type]         [description]
     */
    public function show($branch)
    {
      // need to get branch manager
      //check that this manager is authorized to see this
     
      $this->manager = $this->person->where('user_id','=',auth()->user()->id)->first();
        $myBranches = $this->getBranches();
      if(! array_key_exists($branch, $myBranches)){
        return redirect()->route('dashboard.index')->withError('That is not one of your branches');
      }
      $branch = $this->branch->with('manager')->findOrFail($branch);
      $this->manager = $branch->manager->first();
      $data = $this->getDashBoardData([$branch->id]);
      return $this->displayDashboard($data);

    }
   
    /**
     * [manager description]
     * @param  Request     $request [description]
     * @param  Person|null $manager [description]
     * @return [type]               [description]
     */
    public function manager(Request $request, Person $manager=null)
    {
      
      if($manager){
        $myteam = $this->person->myTeam()->pluck('id')->toArray();
        if(! in_array($manager->id, $myteam)){
          return redirect()->back()->withError('That is not one of your team members');
        }
        $this->manager = $manager;
      }else{
        // need to check that this person is in your team
        

        $this->manager = $this->person->findOrFail(request('manager'));
      }
      
      $team = $this->manager->descendantsAndSelf()
              ->with('branchesServiced')->get();

      $branches = $team->map(function ($mgr){
        return $mgr->branchesServiced->pluck('id')->toArray();
      }); 
      $myBranches = array_unique($branches->flatten()->toArray());

      $data = $this->getDashBoardData($myBranches);
    
      return $this->displayDashboard($data);
    }
    
    /**
     * [getDashBoardData description]
     * @param  array  $myBranches [description]
     * @return [type]             [description]
     */
    private function getDashBoardData(array $myBranches)
    {
    
      $data['team']= $this->myTeamsOpportunities();
      $data['branches'] = $this->getSummaryBranchData($myBranches);
      $data['upcoming'] = $this->getUpcomingActivities($myBranches);       
      $data['funnel'] = $this->getBranchFunnel($myBranches);
      $data['activitychart'] =  $this->getActivityChartData($myBranches);
      $data['pipeline'] = $this->getPipeline($myBranches);
      $data['calendar'] = $this->getUpcomingCalendar($data['upcoming']);
      $data['chart'] = $this->getChartData($myBranches);
      $data['won'] = $this->getWonOpportunities($myBranches);
      if(isset($data['team']['results'])){
        $data['teamlogins'] = $this->getTeamLogins(array_keys($data['team']['results']));
      }
      return $data;
    }
    /**
     * [displayDashboard description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function displayDashboard($data)
    {

       if ($data['branches']->count() > 1) {

                  
           return response()->view('opportunities.mgrindex', compact('data', 'myBranches'));
        
        } else {

           
            return response()->view('branches.dashboard', compact('data', 'myBranches'));
        }


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
    * [getBranchNotes description]
    * @param  [type] $branches [description]
    * @return [type]           [description]
    */
    private function getBranchNotes($branches)
    {

        return Note::whereHas('relatesToLocation', function ($q) use ($branches) {
            $q->whereHas('assignedToBranch', function ($q) use ($branches) {
                $q->whereIn('branch_id', $branches);
            });
        })->with('relatesToLocation', 'writtenBy', 'writtenBy.person')->get();
    }
    
    /**
     * [getBranchFunnel description]
     * @param  array  $branches [description]
     * @return [type]           [description]
     */
    private function getBranchFunnel(array $branches){
    
         return $this->opportunity
                     ->whereHas('branch',function ($q) use($branches){
                        $q->whereIn('branch_id',$branches);
                     })
                     ->whereNotNull('expected_close')
                     ->openFunnel()->get(); 
    }
    /**
     * myTeamsOpportunities Extract and sum all the associated
     * branches statistics
     * @return [associative array] 
     */
    private function myTeamsOpportunities()
    {
      $stats = ['leads',
              'opportunities',
              'booked',
              'won',
              'lost',
              'pipeline',
              'activities'];

      $data['me'] = $this->person->findOrFail($this->manager->id);;
      $data['team'] =  $this->person
      ->where('reports_to','=',$this->manager->id)      
      ->get();
      // get all branch managers
      foreach ($data['team'] as $team){
        $data['branchteam'] = $team->descendantsAndSelf()->withRoles([9])
            ->has('branchesServiced')
            ->with('branchesServiced',
            'branchesServiced.opportunities',
            'branchesServiced.leads',
            'branchesServiced.activities')
            ->get();
        if($data['branchteam']->count()>0){

          $sum = $data['branchteam']->map(function ($manager){

          return [$manager->id=>$manager->branchesServiced->map(function ($branch){

            return ['leads'=>$branch->leads->count(),
              'opportunities'=>$branch->opportunities
              ->where('closed','=',0)->count(),
              'won'=>$branch->opportunities
              ->where('closed','=',1)->where('actual_close','>',Carbon::now()->subMonth(2))->count(),
              'booked'=>$branch->opportunities
              ->where('closed','=',1)->where('actual_close','>',Carbon::now()->subMonth(2))->sum('value'),
              'lost'=>$branch->opportunities->where('closed','=',2)
              ->where('actual_close','>',Carbon::now()->subMonth(2))->count(),
              'pipeline'=>$branch->opportunities->where('closed','=',0)->where('expected_close','>',Carbon::now())->sum('value'),
              'activities'=>$branch->activities->count()];
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
      return $data;
    }
    /**
     * [getSummaryBranchData description]
     * @param  array  $branches [description]
     * @return [type]           [description]
     */
    private function getSummaryBranchData(array $branches){
        
        return $this->branch
        
              ->withCount('opportunities',
                  'leads','activities')
              ->withCount(       
                      ['opportunities',
                          'opportunities as won'=>function($query){
                  
                          $query->whereClosed(1);
                      },
                      'opportunities as lost'=>function($query){
                          $query->whereClosed(2);
                      }]
                  )
          
              ->with('manager','manager.reportsTo')
              ->getActivitiesByType()
              ->whereIn('id',$branches)
              ->get(); 

    }
   
    /**
     * [getChartData description]
     * @param  [type] $branches [description]
     * @return [type]           [description]
     */
    private function getChartData($branches)
    {
       $results =   $this->branch
                    ->whereIn('id',$branches)
                    ->getActivitiesByType(4)
                    ->withCount('leads')
                    ->withCount(       
                            ['opportunities as won'=>function($query){
                        
                                $query->whereClosed(1);
                            }]
                        )
                    ->with(['opportunities'=>function ($q){
                      $q->whereClosed(1);
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
      private function getUpcomingActivities(Array $myBranches)
      {

             $users =  $this->person->myBranchTeam($myBranches);

             return $this->activity->whereIn('user_id',$users)
             ->where('followup_date','>=',Carbon::now())->get();

      }
      /**
       * [getTeamLogins description]
       * @param  array  $team [description]
       * @return [type]       [description]
       */
      private function getTeamLogins(array $team)
      {
        
        $track = $this->person->whereIn('id',$team)
                ->with('userdetails','userdetails.usage')
                ->get();
        
        return $track->map(function($person){
         
            return [$person->fullName() => [
                  'first'=>$person->userdetails->usage->min('lastactivity'),
                  'last'=>$person->userdetails->usage->max('lastactivity'),
                  'count'=>$person->userdetails->usage->count()]];
        });
      }
      /**
       * [getActivityChartData description]
       * @param  Array  $branches [description]
       * @return [type]           [description]
       */
    private function getActivityChartData(Array $branches)
    {

      $branchdata = $this->getWeekActivities($branches)->toArray();
      $branches = [];
      // reformat branch data into array
      $from = $this->activity->getPeriod('thisMonth');
      $to = Carbon::now();

      $keys =  $this->yearWeekBetween($from, $to); 
      
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
          
          $branches[$branch_id] = $this->fillMissingPeriods($branches[$branch_id],$from, $to);
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
     private function fillMissingPeriods($branches,Carbon $from,Carbon $to)
     {
        $keys = $this->yearWeekBetween($from,$to);
        for($i = $from->format('Y-m-d'); $i<= $to->format('Y-m-d');$i+7){
          
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
     
      $data['branches'] = $branches;
      $data['keys'] = $keys;

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
            data: [".implode(",",$info)."]},
            ";
            $i++;
        }

        $data['keys'] = implode(",",$keys);
      
        $data['chartdata'] = str_replace("\r\n","",$chartdata);
       
       return $data;


     }

     /**
      * [Return 2 months worth of activities groupes by branch & week]
      * @param  array  $myBranches [description]
      * @return [type]             [description]
      */
     private function getWeekActivities(array $myBranches){
      $dates = $this->activity->getPeriod('lastWeek');
      dd($dates);
          $weekCount = $this->branch->whereIn('id',$myBranches)
           ->whereHas('activities',function ($q){
              $q->whereBetween('activity_date',[Carbon::now()->subMonth(2),Carbon::now()]);
            })
           ->with(['activities'=>function ($q){
              $q->whereBetween('activity_date',[Carbon::now()->subMonth(2),Carbon::now()]);
           }])->get();

           return  $weekCount->map(function ($branch){
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
    private function getWonOpportunities(array $myBranches){
    
      $won =  $this->opportunity
                ->selectRaw('branch_id,YEARWEEK(actual_close,3) as yearweek,sum(value) as total')
                ->whereNotNull('value')
                ->where('value','>',0)
                ->whereIn('branch_id',$myBranches)
                ->whereBetween('actual_close',[Carbon::now()->subMonth(2),Carbon::now()])
                ->whereClosed(1)
                ->groupBy(['branch_id','yearweek'])
                ->orderBy('branch_id','asc')
                ->orderBy('yearweek','asc')
                ->get();
      $data = [];
      
      foreach ($won as $item){
        
          $data[$item->branch_id][$item->yearweek]=$item->total;
          
      }

      $from = Carbon::now()->subMonth(2);

      $to = Carbon::now();
      $keys =  $this->yearWeekBetween($from, $to);
      $wondata = [];
      foreach(array_unique($won->pluck('branch_id')->toArray()) as $branch_id){
        

        $wondata[$branch_id]= $this->fillMissingPeriods($data[$branch_id],$from, $to);
      
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
    private function getPipeline(array $myBranches){
    
        $pipeline = $this->getPipeLineData($myBranches);
        return $this->formatPipelineData($pipeline);
     }
     /**
      * [getPipeLineData description]
      * @param  array  $myBranches [description]
      * @return [type]             [description]
      */
     private function getPipeLineData(array $myBranches)
     {


     return  $this->opportunity
                    ->selectRaw('branch_id,YEARWEEK(expected_close,3) as yearweek,sum(value) as total')
                    ->whereNotNull('value')
                    ->where('value','>',0)
                    ->whereIn('branch_id',$myBranches)
                    ->where('expected_close','>',Carbon::now())
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
    private function yearWeekBetween($from, $to)
    {
      
      $keys=[];
      for($i = $from->format('Y-m-d'); $i<= $to->format('Y-m-d');$i = $from->addWeek()->format('Y-m-d')){
        $keys[]="'".$i."'";
      }

      return $keys;
    }
}

