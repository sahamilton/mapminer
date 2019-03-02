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
    
       
        if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')){

             $myBranches = $this->branch->all()->pluck('branchname','id')->toArray();

             $data = $this->getSummaryBranchData(array_keys($myBranches));
           
             return response()->view('opportunities.mgrindex', compact('data'));
        } else {
             $myBranches = $this->person->myBranches();
        }
    
        if(count($myBranches)==0){
            return redirect()->route('user.show',auth()->user()->id)->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }
        if ((! auth()->user()->hasRole('branch_manager') && $this->person->myTeam()->count() >1 )) {
            $data['branches'] = $this->getSummaryBranchData(array_keys($myBranches));

           $data['funnel'] = $this->getBranchFunnel($myBranches);
           $data['chart'] = $this->getChartData(array_keys($myBranches));


            $data['chart'] = $this->prepChartData($data['chart']);

            return response()->view('opportunities.mgrindex', compact('data'));
        } else {
               
            $data['upcoming'] = $this->getUpcomingActivities($myBranches);;

            $data['branch'] = $this->getSummaryBranchData(array_keys($myBranches));
            $team = $this->person->myBranchTeam(array_keys($myBranches));
            $weekCount = $this->activity->myTeamsActivities($team)->sevenDayCount()->pluck('activities', 'yearweek')->toArray();
            $data['funnel'] = $this->getBranchFunnel($myBranches);
            $weekCount = $this->activity->myTeamsActivities($team)->sevenDayCount()->pluck('activities', 'yearweek')->toArray();
       
            $data['summary'] = $this->activity->summaryData($weekCount);
        
            return response()->view('branches.dashboard', compact('data', 'myBranches'));
        }
        return redirect()->route('user.show', auth()->user()->id)->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
      
    }

    
    

    private function getBranchNotes($branches)
    {

        return Note::whereHas('relatesToLocation', function ($q) use ($branches) {
            $q->whereHas('assignedToBranch', function ($q) use ($branches) {
                $q->whereIn('branch_id', $branches);
            });
        })->with('relatesToLocation', 'writtenBy', 'writtenBy.person')->get();
    }
    
    public function getBranchFunnel(array $branches){
       
    
         return $this->opportunity
                     ->whereHas('branch',function ($q) use($branches){
                        $q->whereIn('branch_id',array_keys($branches));
                     })
                     ->whereNotNull('expected_close')
                     ->openFunnel()->get(); 


    }
    public function getSummaryBranchData(array $branches){
        
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
       
       // $data['activities'] = $this->branch->whereIn('id',$branches)->get();
        //$data['charts'] = $this->getChartData($branches);


    }
   
    public function chart()
    {
      if(! $branch_ids = $this->person->myBranches()){
        return redirect()->route('home')->withWarning('You are not associated with any branch');
      }
      $branches = array_keys($branch_ids);

      $data = $this->getChartData($branches);


      $data = $this->prepChartData($data);
     
    
      return response()->view('opportunities.chart',compact('data'));
    }

    private function getChartData($branches)
    {
       return  $this->branch
                    ->whereIn('id',$branches)
                    ->getActivitiesByType()
                    ->withCount('leads')
                    ->withCount(       
                            ['opportunities',
                                'opportunities as won'=>function($query){
                        
                                $query->whereClosed(1);
                            },
                            'opportunities as lost'=>function($query){
                                $query->whereClosed(2);
                            }]
                        )
                    ->get();
       
      }

      private function prepChartData($results){


        $string = '';

        foreach ($results as $branch){

          $winloss = $branch->won + $branch->lost;
          
          $string = $string . "[\"".$branch->branchname ."\",  ".$branch->activities->count() .",  ".$branch->opportunities_count.", ".$winloss ."],";
         

        }

        return $string;

      }
          
    private function getUpcomingActivities(Array $myBranches)
    {

           $users =  $this->person->myBranchTeam(array_keys($myBranches));

           return $this->activity->whereIn('user_id',$users)
           ->where('followup_date','>=',Carbon::now())->get();

    }


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


}

