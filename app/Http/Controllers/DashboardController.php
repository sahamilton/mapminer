<?php

namespace App\Http\Controllers;

use App\Dashboard;
use App\Person;
use App\Branch;
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
        if (auth()->user()->hasRole('national_account_manager')) {
            return redirect()->route('namdashboard.index');
        }
        $branchCount = $this->dashboard->checkBranchCount();
        
        if ($branchCount > 1 or auth()->user()->hasRole('admin')) {
            return redirect()->route('mgrdashboard.index');
        } else {
            return redirect()->route('branchdashboard.index');
        }
        


    }
    /**
     * [show description]
     * 
     * @param [type] $branch [description]
     * 
     * @return [type]         [description]
     */
    public function show($branch)
    {
       
        $branch = $this->branch->with('manager')->findOrFail($branch);
        return redirect()->route('branchdashboard.show',  $branch->id);
        
    }
    /**
     * [select description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function select(Request $request)
    {
        
        $this->manager = $this->person->with('manages')
            ->findOrFail(request('manager'));
     
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
        return $this->branch
            ->SummaryStats($this->period)
            ->with('manager', 'manager.reportsTo')
            ->getActivitiesByType($this->period)
            ->whereIn('id', $this->myBranches)
            ->get(); 

    }
   
}
