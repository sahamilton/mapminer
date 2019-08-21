<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Branch;
use App\Person;
use Carbon\Carbon;

class BranchSummaryController extends Controller
{
    public $activity;
    public $branch;
    public $person;

    /**
     * [__construct description]
     * 
     * @param Branch $branch [description]
     */
    public function __construct(Branch $branch, Person $person, Activity $activity)
    {
        $this->branch = $branch;
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
        
        $this->myBranches = $this->_getBranches();

        if (count($this->myBranches) ==1) {
    
            return redirect()->route('branchsummary.show', array_keys($this->myBranches)[0]);

        } elseif (count($this->myBranches) >1) {

            $branches = $this->myBranches;
            $branch = $this->branch->findOrFail(array_keys($this->myBranches)[0]);

            $activities = $this->_getThisWeeksBranchActivities($branch);
            $upcoming = $this->_getUpcomingOpportunities($branch);
           
            return response()->view('branches.summary', compact('upcoming', 'activities', 'branches'));

        } else {
            return redirect()->route('user.show', auth()->user()->id)
                ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }
        
    }
    /**
     * [show description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function show(Branch $branch)
    {
      
        // check that this is my branch
        $activities = $this->_getThisWeeksBranchActivities($branch);
       
        $upcoming = $this->_getUpcomingOpportunities($branch);

      
        return response()->view('branches.summary', compact('upcoming', 'activities'));

    }

    /**
     * [_getUpcomingOpportunities description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    private function _getUpcomingOpportunities(Branch $branch)
    {

        return $branch
            ->load('opportunitiesClosingThisWeek', 'pastDueOpportunities', 'manager', 'manager.userdetails');
    }
    /**
     * [_getThisWeeksBranchActivities description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    private function _getThisWeeksBranchActivities(Branch $branch)
    {

        return $this->activity->where('branch_id', $branch->id)
            ->where('activity_date', '<=', Carbon::now()->addDays(7))
            ->whereNull('completed')
            ->get();
    }

    /**
     * [getBranches description]
     * 
     * @return [type] [description]
     */
    private function _getBranches()
    {
      
        if (auth()->user()->hasRole('admin') 
            or auth()->user()->hasRole('sales_operations')
        ) {
       
            return $this->branch->all()->pluck('branchname', 'id')->toArray();
        
        } else {
      
             return  $this->person->myBranches();
        }
    }
}
