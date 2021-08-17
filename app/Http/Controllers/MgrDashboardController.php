<?php
namespace App\Http\Controllers;

use App\Activity;
use App\ActivityType;
use App\Address;
use App\AddressBranch;
use App\Branch;
use App\Chart;
use App\Company;
use App\Track;
use App\Contact;
use App\Note;
use App\Http\Requests\OpportunityFormRequest;
use App\Opportunity;
use App\Person;
use App\SalesOrg;
use \Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class MgrDashboardController extends DashboardController
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
    public $salesorg;
    public $track;
    public $branchManagerRole = [9];

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
     * @param Salesorg      $salesorg      [<description>]
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
        SalesOrg $salesorg,
        Track $track
    ) {
            $this->activity = $activity;
            $this->address = $address;
            $this->addressbranch = $addressbranch;
            $this->branch = $branch;
            $this->chart = $chart;
            $this->contact = $contact;
            $this->opportunity = $opportunity;
            $this->person = $person;
            $this->salesorg = $salesorg;
            $this->track = $track;    
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        request()->session()->forget('branch');
        // set the period
        $this->_setPeriod();

        // set the manager
        $this->_getManager();

        // if no direct reports this is the incorrect controller.
        if (! $this->manager->directReports->count()) {
            return redirect()->route('dashboard');
        }
        $this->_getBranches();
        
        if (count($this->myBranches) < 2) {
                    return $this->_checkBranches();

        } else {  

            return $this->_displayDashboard();
        }
    }
    /**
     * [_setPeriod description]
     *
     * @return $this->period [<description>]
     */
    private function _setPeriod()
    {
        ray(session('period'));
        if (! $this->period && ! session('period')) {
            $this->period = $this->activity->getPeriod();
        } else {

            $this->period = session('period');
        }
    }
    /**
     * [_getManager description]
     * @return [type] [description]
     */
    private function _getManager()
    {
        if (! session('manager')) {
            session(['manager'=>auth()->user()->id]);
        }

        $this->manager = $this->person
            ->with('directReports')
            ->where('user_id', '=', session('manager'))
            ->firstOrFail();
    }
    /**
     * [_getBranches description]
     * 
     * @return [type] [description]
     */
    private function _getBranches()
    {
        $this->myBranches = $this->manager->getMyBranches();
    }
    /**
     * [_checkBranches If count of branches is less than 2 redirect]
     * 
     * @return [redirect route] [description]
     */
    private function _checkBranches()
    {
        if (count($this->myBranches)==0) {
            //return redirect()->back()->withMessage($this->manager->fullName().' is not assigned to any branches');
            return redirect()->route('user.show', $this->manager->user_id)
                ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }
        if (count($this->myBranches)==1) {
          
            return redirect()->route('dashboard.show', $this->myBranches[0]);
        }
        
    }
    
    /**
     * [selectBranch entry point for drill down to branch]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function selectBranch(Request $request)
    {
       
        if (! $this->period) {
            $this->period= $this->activity->getPeriod();
        } 
        $data = $this->_getDashBoardData([request('branch')]);
        return $this->_displayDashboard($data);

    }

    /**
     * [manager description]
     * 
     * @param Request     $request [description]
     * @param Person|null $manager [description]
     * 
     * @return [type]               [description]
     */
    public function manager(Request $request, Person $manager=null)
    {
        
        request()->session()->forget('branch');
        $this->_setPeriod();
        if ($manager) {
            $this->manager = $manager;
        } else {
          
            $this->manager = $this->person->findOrFail(request('manager'));
        }
        
        $this->_getBranches();
    
        if (count($this->myBranches) < 2) {
            return $this->_checkBranches();

        } else {  

            return $this->_displayDashboard();
        }
        
    }

    
    
    /**
     * [_getDashBoardData description]
     * 
     * @return array $data     [description]
     */
    private function _getDashBoardData()
    {
        $data['me'] = $this->person->findOrFail($this->manager->id);
        $data['team'] = $data['me']->descendants()->limitDepth(1)->get();
        $data['period'] = $this->period;
        $data['branches'] = $this->getSummaryBranchData();
        

        
       
        if (! $data['teamdata'] = $this->_myTeamsData($data)) {
            
            return false;

        }
        //$data['team']->pluck('sales_appointment')->toArray()); 

               // this should go away and incorporate in charts
        $data['charts'] = $this->_getCharts($data);

        $data['charts']['bubble'] = $this->_getBubbleChartData($data['branches']);
        /*
        if (isset($data['team']['results'])) {
            $data['teamlogins'] = $this->_getTeamLogins(array_keys($data['team']['results']));
        }
        */
        
        return $data;
    }
    /**
     * [_displayDashboard description]
     * 
     * @return [type]       [description]
     */
    private function _displayDashboard()
    {
        $data = $this->_getDashBoardData();

        if ($data['branches']->count() > 1) { 
            $reports = \App\Report::publicReports()->get();
            $managers = $data['team'];
       
            return response()->view('opportunities.mgrindex', compact('data', 'reports', 'managers'));
          
        } else {

            $branch = $data['branches']->first();

            return response()->view('branches.dashboard', compact('data', 'branch'));
        }
    }
    
    public function mgrSummary()
    {
        return response()->view('managers.summary');
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
        $fields = array_merge($this->leadFields, $this->opportunityFields, $this->activityFields);

        //* gets the associated branch data (opportunities and leads)
        foreach ($data['team'] as $report) {
            
            $mgrBranches = $report->getMyBranches();
            
            foreach ($fields as $field) {
                $data['teamdata'][$report->fullName()][$field] = $data['branches']->whereIn('id', $mgrBranches)->sum($field);

            }
        }
       
        /* gets the associated people data (activities)
        $this->reports = $data['me']->descendantsAndSelf()->limitDepth(1)->pluck('user_id')->toArray();

        $data['activities'] = $this->getSummaryTeamData($this->period, );
        dd($data['activities']);
        $directReports = $data['me']->descendantsAndSelf()->limitDepth(1)->get();
        foreach ($directReports as $report) {

           foreach ($this->activityFields as $field) {
               
               $data['teamdata'][$report->fullName()][$field] = $data['activities']->where('lft', '>=', $report->lft)->where('rgt', '<=', $report->rgt)->sum($field);
            }
        }*/

        return collect($data['teamdata']);
        
        
    }
    
   
    /**
     * [_getCharts description]
     * 
     * @param array $data [description]
     * 
     * @return [type]       [description]
     */
    private function _getCharts(array $data) 
    {   
        
        if ($data['me']->depth >2) {
            return $this->_getBranchCharts($data);
        }
        return $this->_getTeamCharts($data);
        
    }
    
    private function _getBranchCharts($data)
    {
        $charts['pipelinechart'] = $this->chart->getBranchChart($data, $field='active_value');
        $charts['Top25chart'] = $this->chart->getBranchChart($data, $field='top25_opportunities');
        //$charts['winratiochart'] = [];
        $charts['openleadschart'] = $this->chart->getBranchChart($data, $field='leads_count');
        $charts['newleadschart'] = $this->chart->getBranchChart($data, $field='newbranchleads');
        $charts['activeleadschart'] = $this->chart->getBranchChart($data, $field='active_leads');
        $charts['activitytypechart'] = $this->chart->getBranchesActivityByTypeChart($data);
        $charts['personactivitytypechart'] = $this->chart->getTeamActivityByTypeChart($data);
        return $charts;
    }
    
    private function  _getTeamCharts($data)
    {
        
        $charts['pipelinechart'] = $this->chart->getTeamChart($data, $field='active_value');
        $charts['Top25chart'] = $this->chart->getTeamChart($data, $field='top25_opportunities');
        $charts['openleadschart'] = $this->chart->getTeamChart($data, $field='leads_count');
        $charts['newleadschart'] = $this->chart->getTeamChart($data, $field='newbranchleads');
        $charts['personactivitytypechart'] = $this->chart->getTeamActivityByTypeChart($data);
        //dd(345, $charts['personactivitytypechart']);
        $charts['activeleadschart'] = $this->chart->getTeamChart($data, $field='active_leads');
        return $charts;
    }
     /**
      * [_getChartData description]
      * 
      * @param [type] $results [description]
      * 
      * @return [type]          [description]
      */
    private function _getBubbleChartData($branches) 
    {


        $string = '';

        foreach ($branches as $branch) {
      
            $string = $string . "[\"".$branch->branchname ."\",  ".$branch->sales_appointment .",  ".$branch->won_opportunities.", ". ($branch->won_value ? $branch->won_value : 0) ."],";
         
        }
        
        return $string;

    }

      
      /**
       * [_getTeamLogins description]
       * 
       * @param array $team [description]
       * 
       * @return [type]       [description]
       */
    private function _getTeamLogins(array $team)
    {
      
        $track = $this->person->whereIn('id', $team)
            ->with(
                ['userdetails'=>function ($q) {
                    $q->withCount('usage');
                }
                ]
            )
            ->get();
        
        return $track->map(
            function ($person) {
         
                return [$person->fullName() => [
                      'first'=>$person->userdetails->usage->min('lastactivity'),
                      'last'=>$person->userdetails->usage->max('lastactivity'),
                      'count'=>$person->userdetails->usage->count()]];
            }
        );
    }
      
}
