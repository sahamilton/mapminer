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
        if(count($myBranches)==0){
                return redirect()->route('user.show',auth()->user()->id)
                ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
            }

        $data = $this->getDashBoardData(array_keys($myBranches));
          
      
       return response()->view('opportunities.mgrindex', compact('data'));
        
      
    }
    /*
    


    */
    public function selectBranch(Request $request)
    {

      $data = $this->getDashBoardData([request('branch')]);
      return $this->displayDashboard($data);

    }
    /*
    

    */
    public function show($branch){
      // need to get branch manager
      $branch = $this->branch->with('manager')->findOrFail($branch);
      $this->manager = $branch->manager->first();
      $data = $this->getDashBoardData([$branch->id]);
      return $this->displayDashboard($data);

    }
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
      
      $team = $this->manager->descendantsAndSelf()->with('branchesServiced')->get();
      $branches = $team->map(function ($mgr){
        return $mgr->branchesServiced->pluck('id')->toArray();
      });
      $myBranches = array_unique($branches->flatten()->toArray());

      $data = $this->getDashBoardData($myBranches);
    
      return $this->displayDashboard($data);
    }
    

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
      $data['teamlogins'] = $this->getTeamLogins($myBranches);
     
    
      return $data;
    }
    /*
    
     */
    private function displayDashboard($data)
    {

       if ($data['branches']->count() > 1) {

                  
           return response()->view('opportunities.mgrindex', compact('data', 'myBranches'));
        
        } else {

           
            return response()->view('branches.dashboard', compact('data', 'myBranches'));
        }


    }
   /*
   
    */private function getBranches()
    {
      
      if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')){
       
            return $this->branch->all()->pluck('branchname','id')->toArray();
        
        } else {
      
             return  $this->person->myBranches();
        }
    }
    
    /*
    
     */
    private function getBranchNotes($branches)
    {

        return Note::whereHas('relatesToLocation', function ($q) use ($branches) {
            $q->whereHas('assignedToBranch', function ($q) use ($branches) {
                $q->whereIn('branch_id', $branches);
            });
        })->with('relatesToLocation', 'writtenBy', 'writtenBy.person')->get();
    }
    
    /*
    


     */
    private function getBranchFunnel(array $branches){
    
         return $this->opportunity
                     ->whereHas('branch',function ($q) use($branches){
                        $q->whereIn('branch_id',$branches);
                     })
                     ->whereNotNull('expected_close')
                     ->openFunnel()->get(); 
    }
    /*
    

    */

    private function myTeamsOpportunities()
    {
      $data['me'] = $this->person->findOrFail($this->manager->id);;
      $data['team'] =  $this->person->where('reports_to','=',$this->manager->id)
            
            ->with('branchesServiced',
                'branchesServiced.opportunities',
                'branchesServiced.leads',
                'branchesServiced.activities')   
            ->get();
   

      $sum = $data['team']->map(function ($manager){
        return [$manager->id=>$manager->branchesServiced->map(function ($branch){
          return ['leads'=>$branch->leads->count(),
                  'opportunities'=>$branch->opportunities->where('closed','=',0)->count(),
                  'won'=>$branch->opportunities->where('closed','=',1)->where('actual_close','>',Carbon::now()->subMonth(2))->count(),
                  'booked'=>$branch->opportunities->where('closed','=',1)->where('actual_close','>',Carbon::now()->subMonth(2))->sum('value'),
                  'lost'=>$branch->opportunities->where('closed','=',2)->where('actual_close','>',Carbon::now()->subMonth(2))->count(),
                  'pipeline'=>$branch->opportunities->where('closed','=',0)->where('expected_close','>',Carbon::now())->sum('value'),
                  'activities'=>$branch->activities->count()];
        })];
      });
      foreach ($sum as $manager){
        foreach ($manager as $mgrid=>$items){
          $data['results'][$mgrid]['leads'] = $items->sum('leads');
          $data['results'][$mgrid]['opportunities'] = $items->sum('opportunities');
          $data['results'][$mgrid]['booked'] = $items->sum('booked');
          $data['results'][$mgrid]['won'] = $items->sum('won');
         
          $data['results'][$mgrid]['lost'] = $items->sum('lost');
          $data['results'][$mgrid]['pipeline'] = $items->sum('pipeline');
          $data['results'][$mgrid]['activities'] = $items->sum('activities');
           
        }
      }
      return $data;
    }

    /*
    


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
   
    /*
    

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

      /*
      return string of chart data for single bar chart

      */
      private function prepChartData($results){


        $string = '';

        foreach ($results as $branch){

          $string = $string . "[\"".$branch->branchname ."\",  ".$branch->activities->count() .",  ".$branch->won.", ".$branch->opportunities->sum('value') ."],";
         
        }
        return $string;

      }
    private function getUpcomingCalendar($activities)
    {
        
        return \Calendar::addEvents($activities);
    }    
  

    private function getUpcomingActivities(Array $myBranches)
    {

           $users =  $this->person->myBranchTeam($myBranches);

           return $this->activity->whereIn('user_id',$users)
           ->where('followup_date','>=',Carbon::now())->get();

    }
    private function getTeamLogins(array $branches)
    {
      
      $users =  $this->person->myBranchTeam($branches)->toArray();

      $track =  $this->person->whereIn('user_id',$users)->with('userdetails','userdetails.usage')->get();

      return $track->map(function($person){
       
          return [$person->fullName() => [
                'first'=>$person->userdetails->usage->min('lastactivity'),
                'last'=>$person->userdetails->usage->max('lastactivity'),
                'count'=>$person->userdetails->usage->count()]];
      });
    }

    /*
    
     private function getBranchActivities($branches){
        

        $query = "SELECT branches.id as id, activitytype_id as type, count(activities.id) as activities
            FROM `activities`, address_branch,branches
            where activities.address_id = address_branch.address_id
            and address_branch.branch_id = branches.id
            and activities.activity_date BETWEEN CAST('".Carbon::now()->subMOnth(1)."' AS DATE) AND CAST('".Carbon::now()."' AS DATE)
            and branches.id in (".implode(",",$branches).")
            group by id,activitytype_id";
        $activities =  \DB::select(\DB::raw($query));
        $result = array();
        foreach ($activities as $activity){
            $result[$activity->id][$activity->type] = $activity->activities;
        }

        return $result;

    }
  */
  /*



  */
  private function getActivityChartData(Array $branches)
  {

      $branchdata = $this->getWeekActivities($branches)->toArray();

      $branches = [];
      // reformat branch data into array
      $from = Carbon::now()->subMonth(2);
      $to = Carbon::now();

      $keys =  $this->yearWeekBetween($from, $to); 
      foreach($branchdata as $branch){

          $branch_id = implode(",",array_keys($branch));
          foreach ($branch[implode(",",array_keys($branch))] as $item){

            foreach($item as $period=>$el){

              $branches[$branch_id][$period]= $el->count();

            }
          }
          
          // fill any missing periods with zeros
          // 
          
          $branches[$branch_id] = $this->fillMissingPeriods($branches[$branch_id],$from, $to);
          // sort branch array in date sequence
        ksort($branches[$branch_id]);
     
      }
     // if too many for graph return table
      if(count($branches) < 10){
        return $this->formatChartData($branches,$keys);
      }
      return $this->formatActivityTableData($branches,$keys);
       

     }


     private function fillMissingPeriods($branches,Carbon $from,Carbon $to)
     {
        $keys = $this->yearWeekBetween($from,$to);
        for($i = $from->format('YW'); $i<= $to->format('YW');$i++){
          
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

     private function formatActivityTableData(array $branches,array $keys)
     {
     
      $data['branches'] = $branches;
      $data['keys'] = $keys;

      return $data;
     

     }

     private function formatChartData(Array $branches,array $keys){

 
       /* this is the chart format required

             {
                label: "Harpo",
                backgroundColor: "blue",
                data: [3,7,4]
            },
        */      
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
     /*
      Return 2 months worth of activities groupes by branch & week

      */
     private function getWeekActivities(array $myBranches){
          $weekCount = $this->branch->whereIn('id',$myBranches)
           ->whereHas('activities',function ($q){
              $q->whereBetween('activity_date',[Carbon::now()->subMonth(2),Carbon::now()]);
            })
           ->with(['activities'=>function ($q){
              $q->whereBetween('activity_date',[Carbon::now()->subMonth(2),Carbon::now()]);
           }])->get();

           return  $weekCount->map(function ($branch){
              return [$branch->id=>[$branch->activities->groupBy(function ($activity) {
                   return $activity->activity_date->format('YW');
              })]];
             });
     }
     /*
     Return 2 months of won opportunities
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
    
    private function getPipeline(array $myBranches){
    
        $pipeline = $this->getPipeLineData($myBranches);
        return $this->formatPipelineData($pipeline);
     }
     
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

    private function yearWeekBetween($from, $to)
    {
      
      $keys=[];
      for($i = $from->format('YW'); $i<= $to->format('YW');$i = $from->addWeek()->format('YW')){
        $keys[]=$i;
      }

      return $keys;
    }
}

