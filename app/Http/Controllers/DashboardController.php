<?php

namespace App\Http\Controllers;

use App\Dashboard;
use App\Person;
use App\Branch;
use App\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $dashboard;
    public $person;
    public $branch;

    /**
     * [__construct description]
     * 
     * @param Dashboard $dashboard [description]
     * @param Person    $person    [description]
     * @param Branch    $branch    [description]
     */
    public function __construct(Dashboard $dashboard,Person $person,Branch $branch)
    {
        $this->dashboard = $dashboard;
        $this->person = $person;
        $this->branch = $branch;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $myRoles = auth()->user()->roles->pluck('name')->toArray();
       

        switch ($myRoles[0]) {

        case 'national_account_manager':
            return redirect()->route('namdashboard.index');
            break;
        
        case 'branch_manager';
            return redirect()->route('branchdashboard.index');
            break;

        case 'admin':
            $managers = $this->_selectDashboard();
            return response()->view('dashboard.select', compact('managers'));
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
        
        $role =  $manager->userdetails->roles->pluck('name')->toArray();
        
        switch ($role[0]) {

        case 'national_account_manager':
            return redirect()->route('namdashboard.index');
            break;
        
        case 'branch_manager';
        
            return redirect()->route('branchdashboard.index');
            break;

        case 'admin':
            $managers = $this->_selectDashboard();
            return response()->view('dashboard.select', compact('managers'));
            break;

        default:
            return redirect()->route('mgrdashboard.index');
            break;

        }
        
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
     * [_getSummaryBranchData description]
     * 
     * @return [type]           [description]
     */
    protected function getSummaryBranchData() 
    {
       
        
        $leadFields = [
            'leads',
        ];
        $opportunityFields =[
            "lost_opportunities",
            "open_opportunities",
            "top25_opportunities",
            "won_opportunities",
            "active_value",
            "lost_value",
            "won_value",
        ];
        return $this->branch->select('id', 'branchname')
            ->SummaryLeadStats($this->period, $leadFields)
            ->SummaryOpportunities($this->period, $opportunityFields)
            ->SummaryActivities($this->period)
            ->with('manager', 'manager.reportsTo')
            ->whereIn('id', $this->myBranches)
            ->get(); 

    }

    private function _selectDashboard()
    {
        return $this->person->managers([3,4,6,7,9]);
    }
   
}
