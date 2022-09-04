<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchDashboard implements DashboardInterface {
    use PeriodSelector;
    public $branch;
    public $person;
    public function __construct()
    {
        
        
    }

    public function isValid(Person $person)
    {
        $this->person = $person;
        $myBranches = $person->getMyBranches();
        
        if (count($myBranches) > 0) {
           
            return true;
        }
        return false;
    }

    public function getDashBoardData(Branch $branch = null)
    {
        if (! $branch) {
            $branchIds = $this->person->getMyBranches();
        } else {
            $branchIds = [$branch->id];

        }   
        return Branch::find($branchIds);
    }

    public function getView()
    {
        return 'dashboards\branchdashboard';
    }

}