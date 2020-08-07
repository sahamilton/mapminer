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
    public $period;
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

    public function mount($person, array $period)
    {
        $this->person = $person->id;
        $this->myBranches = $person->getMyBranches();
        $this->period = $period;
        $this->setPeriod = $period['period'];
    }
    
    public function render()
    {
        if ($this->setPeriod != $this->period['period']) {

           $this->_setPeriod(); 
        
        }
        return view('livewire.branch-opportunity-table',
            ['branches'=>Branch::summaryOpportunities($this->period)
                ->whereIn('branches.id', $this->myBranches)
                ->search($this->search)
                ->with('manager')
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]

        );
    }

    private function _setPeriod()
    {
        $this->period = Person::where('id', $this->person)->first()->getPeriod($this->setPeriod);
    }
}
