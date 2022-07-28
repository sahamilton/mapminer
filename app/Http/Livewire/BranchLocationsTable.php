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
    
  
    

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingDistance()
    {
        $this->resetPage();
    }
    public function updatingBranch()
    {
        $this->resetPage();
    }
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    public function mount(int $branch)
    {
        
               
        $this->branch = Branch::findOrFail($branch);
        $this->distance = $this->branch->radius;
       
    }



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

    

    private function _getBranch()
    {
        $this->branch = Branch::findOrFail($this->branch_id);
    }

    private function _checkDistance()
    {
        if (! $this->distance) {
            $this->distance = $this->branch->radius;
        }
    }
}
