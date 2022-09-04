<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use Livewire\Component;

class BranchReassign extends Component
{
    public Branch $branch;

    public function mount(Branch $branch)
    {
        $this->branch = $branch;
    }

    public function render()
    {
        return view('livewire.branch.branch-reassign', 
            [

                'oldbranch' => $this->branch->load('openActivities', 'openOpportunities', 'allLeads', 'branchTeam'),
                'nearby' =>Branch::newNearby($this->branch, 500, 5)->get(),


            ]
        );
    }
    // show team
    // show leads
    // show open activities
    // show open opportunities
    // 
    // reassign team
    // 
    // release leads
    // 
    // transfer leads
    // 
    // close activities
    // 
    // close open opportunities
}
