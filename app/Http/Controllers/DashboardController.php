<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Person;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $dashboard;
    public $person;
    public $branch;
    public $user;
    public $reports;
    public $leadFields = [
            'leads',
            'active_leads',
            'newbranchleads'
        ];
    public $opportunityFields =[
            "lost_opportunities",
            "open_opportunities",
            "top25_opportunities",
            "won_opportunities",
            "active_value",
            "lost_value",
            "won_value",
        ];
    public $activityFields = [

            '4'=>'sales_appointment',
            '5'=>'stop_by',
            '7'=>'proposal',
            '10'=>'site_visit',
            '13'=>'log_a_call',
            '14'=>'in_person',
            'activities_count'



        ];

    /**
     * [__construct description]
     * 
     * @param Dashboard $dashboard [description]
     * @param Person    $person    [description]
     * @param Branch    $branch    [description]
     */
    public function __construct(
        Dashboard $dashboard,
        Person $person,
        Branch $branch,
        User $user
    ) {
        $this->dashboard = $dashboard;
        $this->person = $person;
        $this->branch = $branch;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $myTeam = auth()->user()->person->myTeam()->get()->pluck('user_id')->toArray();
        
        if (! session('manager') or ! in_array(session('manager'), $myTeam)) {
            session(['manager'=>auth()->user()->id]);
        }
        
        $myRoles = $this->user->findOrFail(session('manager'))
            ->roles->pluck('name')
            ->toArray();
        
        switch ($myRoles[0]) {

        case 'national_account_manager':
            return redirect()->route('namdashboard.index');
            break;
        
        case 'branch_manager':
        case 'staffing_specialist';
            
            return redirect()->route('branchdashboard.index');
            break;

        case 'admin':
        case 'serviceline_manager':
            $managers = $this->_selectDashboard();
            
            return response()->view('dashboards.select', compact('managers'));
            break;

        default:
            
            return redirect()->route('mgrdashboard.index');
            break;

        }       


    }
    /**
     * [show description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function select(Request $request)
    {
        $manager = $this->person->with('userdetails.roles')->findOrFail(request('manager'));
        session(['manager'=>$manager->user_id]);
        $role =  $manager->userdetails->roles->pluck('name')->toArray();
        
        switch ($role[0]) {

        case 'national_account_manager':
            return redirect()->route('namdashboard.index');
            break;
        
        case 'branch_manager';
        
            return redirect()->route('branchdashboard.index');
            break;

        case 'admin':
        case 'serviceline_manager':

            $managers = $this->_selectDashboard();
            return response()->view('dashboards.select', compact('managers'));
            break;

        default:

            return redirect()->route('mgrdashboard.index');
            break;

        }
        
    }

    public function reset()
    {
        
        session()->forget(['manager','branch', 'period']);
        return redirect()->route('dashboard.index');
    }
    /**
     * [select description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function show(Branch $branch)
    {
       
        $this->manager = $this->person->with('manages')
            ->findOrFail(auth()->user()->person->id);
       
        $branchCount = $this->dashboard->checkBranchCount($this->manager);
        
        if ($branchCount > 1) {
            
            return redirect()->route('manager.dashboard', $this->manager->id);
        } elseif ($branchCount==1 && count($this->manager->manages) >0) {
        
            return redirect()
                ->route('branchdashboard.show', $this->manager->manages->first()->id);
        } else {
            return redirect()->back()
                ->withWarning($this->manager->fullName() . 'is not associated with any branches');
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
      
        $this->period = $this->person->setPeriod(request('period'));

        return redirect()->route('dashboard.index');
    }

    /**
     * [setManager description]
     * 
     * @param Request $request [description]
     *
     * @return redirect [<description>]
     */
    public function setManager(Request $request)
    {
        $manager = $this->person->findOrFail(request('manager'));

        session(['manager'=>$manager->user_id]);
       
        return redirect()->route('dashboard.index');
    }

    /**
     * [_getSummaryBranchData description]
     * 
     * @return [type]           [description]
     */
    protected function getSummaryBranchData() 
    {
        
        return $this->branch->select('id', 'branchname')
            ->SummaryLeadStats($this->period, $this->leadFields)
            ->SummaryOpportunities($this->period, $this->opportunityFields)
            ->SummaryActivities($this->period, $this->activityFields)
            ->with('manager', 'manager.reportsTo')
            ->whereIn('id', $this->myBranches)
            ->get(); 

    }
    /**
     * [_getSummaryBranchData description]
     * 
     * @return [type]           [description]
     */
    protected function getSummaryTeamData() 
    {
        
        return $this->person->selectRaw('persons.id, concat_ws(" ",firstname, lastname) as manager, lft, rgt')
            
            ->SummaryActivities($this->period, $this->activityFields)
            
            ->whereIn('user_id', $this->reports)
            ->get(); 

    }

    private function _selectDashboard()
    {
        return auth()->user()->person->managers([3,4,6,7,9]);
    }
   
}
