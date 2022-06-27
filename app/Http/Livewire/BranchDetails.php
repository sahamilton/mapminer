<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Branch;
class BranchDetails extends Component
{
    

   
    public $branch_id;
    
    

    public function mount(int $branch_id)
    {
        $this->branch_id = $branch_id; 

    }
    
    public function render()
    {
       
        return view('livewire.branch-details',
            [
                'branch'=>Branch::query()

                    ->with('branchteam.reportsto')
            
                    
                    ->findOrFail($this->branch_id),
                'branches'=>Branch::orderBy('branchname')->pluck('branchname', 'id')
                ->toArray(),


        ]);
    }
}
