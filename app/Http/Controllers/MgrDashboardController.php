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

        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }
        if (auth()->user()->hasRole(['admin'])) {
            $this->manager = $this->salesorg->getCapoDiCapo();
        } else {
            $this->manager = $this->person->where('user_id', '=', auth()->user()->id)->firstOrFail();
        }
        
        // get associated branches
    
        $this->myBranches = array_keys($this->_getBranches());
        
        if (count($this->myBranches) < 2) {
                    return $this->_checkBranches();

        } else {   
            $data = $this->_getDashBoardData();
            $reports = \App\Report::publicReports()->get();
            $managers = $this->manager->load('directReports')->directReports;
            
            return response()->view('opportunities.mgrindex', compact('data', 'reports', 'managers'));
        }
    }
    /**
     * [_checkBranches description]
     * 
     * @return [type] [description]
     */
    private function _checkBranches()
    {
        
        if (count($this->myBranches)==1) {
            
            return redirect()->route('branchdashboard.show', $this->myBranches[0]);
        
        } elseif (count($this->myBranches)==0) {
                return redirect()->route('user.show', auth()->user()->id)
                    ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }
        
    }
    /**
     * [setPeriod description]
     * 
     * @param Request $request [description]
     *
     * @return redirect [<description>]
     */
    public function setPeriod(Request $request)
    {
      
        $this->period = $this->activity->setPeriod(request('period'));

        return redirect()->route('newdashboard.index');
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
       
        if (!$this->period) {
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
        if (! $this->period) {
            $this->period = $this->activity->getPeriod();
        }

        if ($manager) {
            $this->manager = $manager;
        } else {
          
            $this->manager = $this->person->findOrFail(request('manager'));
        }
        // This should be getMyBranches
        $this->myBranches = $manager->getMyBranches();
        /*$team = $this->manager->descendantsAndSelf()
            ->with('branchesServiced')->get();
        
        $branches = $team->map(
            function ($mgr) {
                return $mgr->branchesServiced->pluck('id')->toArray();
            }
        ); 
        
        $this->myBranches = array_unique($branches->flatten()->toArray());*/
       
        if (count($this->myBranches)==0) {
            return redirect()->back()->withMessage($this->manager->fullName().' is not assigned to any branches');

        }
        if (count($this->myBranches)==1) {
          
            return redirect()->route('dashboard.show', $this->myBranches[0]);
        }
        $data = $this->_getDashBoardData();

        return $this->_displayDashboard($data);
    }
    
    /**
     * [_getDashBoardData description]
     * 
     * @return array $data     [description]
     */
    private function _getDashBoardData()
    {
        $myBranches = $this->myBranches;

        
        $data['period'] = $this->period;
        $data['branches'] = $this->getSummaryBranchData();
        $data['team']= $this->_myTeamsOpportunities($data['branches']);

        // this should go away and incorparte in charts
        $data['chart'] = $this->_getChartData($data['branches']);
        
        if (isset($data['team']['results'])) {
            $data['teamlogins'] = $this->_getTeamLogins(array_keys($data['team']['results']));
        }
    
        return $data;
    }
    /**
     * [_displayDashboard description]
     * 
     * @param [type] $data [description]
     * 
     * @return [type]       [description]
     */
    private function _displayDashboard($data)
    {
        
        if ($data['branches']->count() > 1) { 
            $reports = \App\Report::publicReports()->get();
            $managers = $data['team']['me']->directReports()->get();
            
            return response()->view('opportunities.mgrindex', compact('data', 'myBranches', 'reports', 'managers'));
          
        } else {

            $branch = $data['branches']->first();

            return response()->view('branches.dashboard', compact('data', 'branch'));
        }
    }
    /**
     * [_getBranches description]
     * 
     * @return [type] [description]
     */
    private function _getBranches()
    {
      
        if (auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations')) {
       
            return $this->branch->all()->pluck('branchname', 'id')->toArray();
        
        } else {
      
             return $this->person->myBranches();
        }
    }
    
    
    /**
     * [_myTeamsOpportunities description]
     * 
     * @param Collection $branchdata [description]
     * 
     * @return [type]                 [description]
     */
    private function _myTeamsOpportunities(Collection $branchdata)
    {
       
        $stats = ['leads',
                'opportunities',
                'Top25',
                'booked',
                'won',
                'lost',
                'pipeline',
                'activities'];
        $teamroles = [14,6,7,3,9];
        $data['me'] = $this->person->findOrFail($this->manager->id);
        // this might return branch managers with no branches!
        $data['team'] =  $this->person
            ->where('reports_to', '=', $this->manager->id)
            ->with('branchesServiced')
            ->withRoles($teamroles) 
            ->get();
       
        // get all branch managers
        $branchManagerRole = 9;
       
        foreach ($data['team'] as $team) {
            
            
            
            $data['branchteam'] = $team->descendantsAndSelf()
                ->withRoles([$branchManagerRole])
                ->has('branchesServiced')
                ->with('branchesServiced')
                ->get();

            
            $branches = $data['branchteam']->map(
                function ($person) {
                    return $person->branchesServiced->pluck('id');
                }
            );
            
            $branches = $branches->flatten();
              
            if ($data['branchteam']->count() > 0 ) {
                $data['data'][$team->id]['leads'] = $branchdata
                      ->whereIn('id', $branches)
                      ->sum('leads_count');
                
                $data['data'][$team->id]['activities'] = $branchdata
                      ->whereIn('id', $branches)
                      ->where('completed', 1)
                      ->sum('activities_count');

                $data['data'][$team->id]['activitiestype'] = $branchdata
                    ->whereIn('id', $branches)
                    ->map(
                        function ($branch) {
                              return $branch->activities->groupBy('activitytype_id')->toArray();
                        }
                    );

                $data['data'][$team->id]['won'] = $branchdata
                      ->whereIn('id', $branches)
                      ->sum('won');

                $data['data'][$team->id]['lost'] = $branchdata
                      ->whereIn('id', $branches)
                      ->sum('lost');

                $data['data'][$team->id]['Top25'] = $branchdata
                      ->whereIn('id', $branches)
                      ->sum('Top25');

                $data['data'][$team->id]['open'] = $branchdata
                      ->whereIn('id', $branches)
                      ->sum('open');

            }
        }
    
        $data = $this->_getCharts($data);

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
        $data['activitytypechart'] = $this->chart->getTeamActivityByTypeChart($data);
        
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
          
            $string = $string . "[\"".$branch->branchname ."\",  ".$branch->salesappts .",  ".$branch->won.", ". ($branch->wonvalue ? $branch->wonvalue : 0) ."],";
         
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
        dd($track->first());
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