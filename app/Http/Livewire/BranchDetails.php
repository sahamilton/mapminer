<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Branch;
class BranchDetails extends Component
{
    

   
    public $branch_id;
    public $myBranches;


    public $noheading=false;
    protected $listeners = ['refreshBranch'=>'changeBranch', 'refreshPeriod'=>'changePeriod'];
    /**
     * [changeBranch description]
     * 
     * @param [type] $branch_id [description]
     * 
     * @return [type]            [description]
     */
    public function changeBranch($branch_id)
    {
         
         $this->branch_id = $branch_id;

    }
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
        $this->myBranches = auth()->user()->person->getMyBranches();
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        @ray($this->myBranches);
        return view(
            'livewire.branch.branch-details',
            [
                'branch'=>Branch::query()
                    ->with('branchteam.reportsto', 'oraclelocation.mapminerUser')
                    ->when(
                        $this->branch_id !='all', function ($q) {
                            $q->where('id', $this->branch_id);
                        }, function ($q) {
                            $q->whereIn('id', $this->myBranches);
                        }
                    )->get(),
                
                'branches'=>Branch::orderBy('branchname')->pluck('branchname', 'id')
                    ->toArray(),


            ]
        );
    }
}
