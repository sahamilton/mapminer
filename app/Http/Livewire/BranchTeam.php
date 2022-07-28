<?php

namespace App\Http\Livewire;

use App\Branch;
use Livewire\Component;

class BranchTeam extends Component
{

    public array $branches;
    public string $branch_id;
   
    public function mount($branch_id = null)
    {
        
        $this->branches = auth()->user()->person->getMyBranches();
        
        if (isset($branch_id)) {
            $this->branch = Branch::findOrFail($branch_id);
        } else {
            $this->branch = Branch::findOrFail(reset($this->branches));
        }
        
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        return view(
            'livewire.branch.branch-team',
            [

                'branch'=>Branch::with(
                    'manager.directReports.userdetails.oracleMatch', 
                    'manager.reportsTo.directReports.userdetails.roles'
                )->findOrFail($this->branch_id),
            ]
        );
    }
}
