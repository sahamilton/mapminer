<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityType;
use App\Address;
use App\AddressBranch;
use App\Branch;
use App\Company;
use App\Contact;
use App\Note;
use App\Http\Requests\OpportunityFormRequest;
use App\Opportunity;
use App\Person;
use \Carbon\Carbon;
use Illuminate\Http\Request;

class BranchDashboardController extends Controller
{
    public $address;
    public $addressbranch;
    public $branch;
    public $contact;
    public $opportunity;
    public $activity;
    public $person;
    public $keys;


    public function __construct(
        Activity $activity,
        Address $address,
        AddressBranch $addressbranch,
        Branch $branch,
        Contact $contact,
        Opportunity $opportunity,
        Person $person
    ) {
        $this->address = $address;
        $this->addressbranch = $addressbranch;
        $this->branch = $branch;
        $this->contact = $contact;
        $this->opportunity = $opportunity;
        $this->person = $person;
        $this->activity = $activity;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $myBranches = $this->getBranches();
        
        if(count($myBranches)==0){
            return redirect()->route('user.show',auth()->user()->id)
            ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }

        $data['branches'] = $this->getSummaryBranchData(array_keys($myBranches));
        $data['upcoming'] = $this->getUpcomingActivities(array_keys($myBranches));       
        $data['funnel'] = $this->getBranchFunnel(array_keys($myBranches));
        $data['activitychart'] =  $this->getActivityChartData(array_keys($myBranches));

        
        if (count($myBranches) > 1) {
                     
           return response()->view('opportunities.mgrindex', compact('data', 'myBranches'));
        
        } else {
               
            return response()->view('branches.dashboard', compact('data', 'myBranches'));
        }
      
    }

    private function getBranches()
    {
      if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')){

            return $this->branch->all()->pluck('branchname','id')->toArray();
        
        } else {
             return  $this->person->myBranches();
        }
    }
    

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
    public function getBranchFunnel(array $branches){
    
         return $this->opportunity
                     ->whereHas('branch',function ($q) use($branches){
                        $q->whereIn('branch_id',$branches);
                     })
                     ->whereNotNull('expected_close')
                     ->openFunnel()->get(); 
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
    
        ->with('manager')
        ->getActivitiesByType()
        ->whereIn('id',$branches)
        ->get(); 

    }
   
    /*
    

    */
/*private function getChartData($branches)
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
      /*private function prepChartData($results){


        $string = '';

        foreach ($results as $branch){

          $string = $string . "[\"".$branch->branchname ."\",  ".$branch->activities->count() .",  ".$branch->won.", ".$branch->opportunities->sum('value') ."],";
         
        }
        return $string;

      }*/
          
    private function getUpcomingActivities(Array $myBranches)
    {

           $users =  $this->person->myBranchTeam(array_keys($myBranches));

           return $this->activity->whereIn('user_id',$users)
           ->where('followup_date','>=',Carbon::now())->get();

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
  private function getActivityChartData(Array $branches){
        $branchdata = $this->getWeekActivities($branches)->toArray();
     
        $this->keys=[];
        $branches = [];
        foreach($branchdata as $branch){
          $branch_id = implode(",",array_keys($branch));
          foreach ($branch[implode(",",array_keys($branch))] as $item){
            foreach($item as $period=>$el){
              $branches[$branch_id][$period]= $el->count();
            }
          }
        
            for($i = Carbon::now()->subMonth(2)->format('YW'); $i< Carbon::now()->format('YW');$i++){
                if(! in_array($i,$this->keys)){
                    $this->keys[]=$i;
                }
                if(! array_key_exists($i,$branches[$branch_id])) {
                    $branches[$branch_id][$i] = 0;
                }
           
            }
            ksort($branches[$branch_id]);
         
      }
      
       return $this->formatActivityChartData($branches);
     }

     private function formatActivityChartData(Array $branches){

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
        $data['keys'] = implode(",",$this->keys);
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
}

