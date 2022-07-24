<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Branch;
class BranchDetails extends Component
{
    

   
    public $branch_id;
    public $noheading=false;
    
    /**
     * [mount description]
     * 
     * @param int  $branch_id [description]
     * @param bool $noheading [description]
     * 
     * @return [type]            [description]
     */
    public function mount(int $branch_id, $noheading=null)
    {
        $this->branch_id = $branch_id; 
        $this->noheading=$noheading;
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
       
        return view(
            'livewire.branch-details',
            [
                'branch'=>Branch::query()
                    ->with('branchteam.reportsto', 'oraclelocation.mapminerUser')
                    ->findOrFail($this->branch_id),
                
                'branches'=>Branch::orderBy('branchname')->pluck('branchname', 'id')
                    ->toArray(),


            ]
        );
    }
}
