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
        
        $data['period'] = $this->period;
        $data['branches'] = $this->getSummaryBranchData();

        if (! $data['team'] = $this->_myTeamsData($data['branches'])) {
            
            return false;

        }
     
        // this should go away and incorporate in charts
        $data['chart'] = $this->_getChartData($data['branches']);
        //ray($data['chart']);
        if (isset($data['team']['results'])) {
            $data['teamlogins'] = $this->_getTeamLogins(array_keys($data['team']['results']));
        }
       
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
            $managers = $data['team']['me']->directReports()->get();
       
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
     * [_myTeamsData description]
     * 
     * @param Collection $branchdata [description]
     * 
     * @return [type]                 [description]
     */
    private function _myTeamsData($branchdata)
    {
        $data['branches'] = $branchdata;
        $teamroles = [14,6,7,3,9];
        $data['me'] = $this->person->findOrFail($this->manager->id);
        // this might return branch managers with no branches!
        $data['team'] =  $this->person
            ->where(
                function ($q) {
                    $q->where('reports_to', $this->manager->id)
                        ->orWhere('persons.id', $this->manager->id);
                }
            )
            ->summaryActivities($this->period)
            ->with('branchesServiced')
            ->withRoles($teamroles) 
            ->get();
        
        if (! $data['team']->count()) {
            return false;
        }
        // get all branch managers
        
        foreach ($data['team'] as $team) {

            $data = $this->_getBranchManagerData($team, $branchdata, $data);

        }
        
        $data = $this->_getCharts($data);

        return $data;
    }
    /**
     * [_getBranchManagerData description]
     * 
     * @param  [type] $team [description]
     * 
     * @return [type]       [description]
     */
    private function _getBranchManagerData(Person $team, Collection $branchdata, array $data)
    {
        
        $data['branchteam'] = $team->descendantsAndSelf()
            ->withRoles([$this->branchManagerRole])
            ->has('branchesServiced')
            ->with('branchesServiced')
            ->get();
            
        
        $branches = $data['branchteam']->map(
            function ($person) {
                return $person->branchesServiced->pluck('id');
            }
        )->flatten();
       
        //$branches = $branches;

        $mybranchdata = $branchdata->filter(
            function ($branch) use ($branches) {
                return in_array($branch->id, $branches->toArray());
            }
        );

        if ($data['branchteam']->count() > 0 ) {
            $data['data'][$team->id]['leads'] = $mybranchdata->sum('leads_count');
            $data['data'][$team->id]['activities'] = $mybranchdata->sum('activities_count');
            $data['data'][$team->id]['activitiestype'] = $this->_getSummaryBranchActivitiesByType($mybranchdata);       
            $data['data'][$team->id]['won'] = $mybranchdata->sum('won_opportunities');
            $data['data'][$team->id]['lost'] = $mybranchdata->sum('lost_opportunities');
            $data['data'][$team->id]['Top25'] = $mybranchdata->sum('top25_opportunities');
            $data['data'][$team->id]['open'] = $mybranchdata->sum('open_opportunities');
            
        }
        return $data;
    }
    /**
     * [_getSummaryBranchActivitiesByType description]
     * 
     * @param Collection $mybranchdata [description]
     * 
     * @return [type]                   [description]
     */
    private function _getSummaryBranchActivitiesByType(Collection $mybranchdata)
    {
        
        $types =$mybranchdata->first()->activityFields;
       
        foreach ($types as $type) {
            $type=str_replace(" ", "_", strtolower($type));
            $data[$type] = $mybranchdata->sum($type);


        }
        return $data;
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
        
        $data['activities'] = $this->chart->getTeamActivityChart($data);

        $data['pipelinechart'] = $this->chart->getTeamPipelineChart($data);
        $data['Top25chart'] = $this->chart->getTeamTop25Chart($data);
        $data['winratiochart'] = $this->chart->getWinRatioChart($data);
        $data['openleadschart'] = $this->chart->getOpenLeadsChart($data);
        //dd($this->chart->getTeamActivityByTypeChart($data));
        $data['personactivitytypechart'] = $this->chart->getTeamActivityByTypeChart($data);
        $data['activitytypechart'] = $this->chart->getBranchesActivityByTypeChart($data);
        //dd($data['activitytypechart'], $data['']);
        //$data['activitytypechart'] = $this->chart->getTeamActivityByTypeChart($data);
        return $data;
    }
    

    
    
     /**
      * [_getChartData description]
      * 
      * @param [type] $results [description]
      * 
      * @return [type]          [description]
      */
    private function _getChartData($results) 
    {


        $string = '';

        foreach ($results as $branch) {
      
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
