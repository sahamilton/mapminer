<?php

namespace App\Http\Livewire;
use App\Person;
use App\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class BranchOpportunityTable extends Component
{
    
    use WithPagination;
    public $perPage = 10;
    public $sortField = 'branches.id';
    public $sortAsc = true;
    public $search = '';
    public $branch;
    public $period = 'All';
    public $setPeriod;
    public $myBranches;
    public $manager;
    public $person;
    public $accounttype=false;

    public function updatingSearch()
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

    public function mount()
    {
        $person = new Person();
        $this->myBranches = $person->myBranches();
        $this->branch_id = array_key_first($this->myBranches);
        
    }
    
    public function render()
    {
        if (! $this->setPeriod or $this->setPeriod != $this->period['period']) {

           $this->_setPeriod(); 
        
        }
        
        return view('livewire.branch-opportunity-table',
            ['branches'=>Branch::summaryOpportunities($this->period)
                ->whereIn('branches.id', array_keys($this->myBranches))
                ->search($this->search)
                ->with('manager')
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]

        );
    }

    private function _setPeriod()
    {
        $this->period = Person::where('user_id', auth()->user()->id)->first()->getPeriod($this->setPeriod);
    }
}
