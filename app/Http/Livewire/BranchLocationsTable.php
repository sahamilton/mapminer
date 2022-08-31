<?php

namespace App\Http\Livewire;
use App\Branch;
use App\Address;
use App\Person;
use Livewire\Component;
use Livewire\WithPagination;
class BranchLocationsTable extends Component
{
    
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'distance';
    public $sortAsc = true;
    public $search = '';
    public $branch;
    public $range;
    public $distance;
    public $accounttype=false;
    
  
    
    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch() :void
    {
        $this->resetPage();
    }
    /**
     * [updatingDistance description]
     * 
     * @return [type] [description]
     */
    public function updatingDistance() :void
    {
        $this->resetPage();
    }
    /**
     * [updatingBranch description]
     * 
     * @return [type] [description]
     */
    public function updatingBranch() :void
    {
        $this->resetPage();
    }
    /**
     * [sortBy description]
     * 
     * @param [type] $field [description]
     * 
     * @return [type]        [description]
     */
    public function sortBy($field) :void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    /**
     * [mount description]
     * 
     * @param int $branch [description]
     * 
     * @return [type]         [description]
     */
    public function mount(int $branch) :void
    {
        
               
        $this->branch = Branch::findOrFail($branch);
        $this->distance = $this->branch->radius;
       
    }


    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->_checkDistance();
        return view(
            'livewire.branch.branch-locations-table', [
            'addresses'=>
                Address::query()
                    ->search($this->search)
                    ->nearby($this->branch, $this->distance)
                    ->whereDoesntHave('assignedToBranch')
                    ->with('company', 'industryVertical')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),

                
            ]
        );
    }

    
    /**
     * [_getBranch description]
     * 
     * @return [type] [description]
     */
    private function _getBranch()
    {
        $this->branch = Branch::findOrFail($this->branch_id);
    }
    /**
     * [_checkDistance description]
     * 
     * @return [type] [description]
     */
    private function _checkDistance()
    {
        if (! $this->distance) {
            $this->distance = $this->branch->radius;
        }
    }
}
