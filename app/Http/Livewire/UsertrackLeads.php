<?php

namespace App\Http\Livewire;
use App\Models\Address;
use App\Models\Branch;
use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;

class UsertrackLeads extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = '';
    public $period;
    public $setPeriod;
    public $user;

 
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
    public function mount($user, $period)
    {
        
        $this->user = $user;
        $this->period = $period;
        $this->setPeriod = $period['period'];
        
     
    }
    public function render()
    {
        
        $this->_setPeriod();
        return view(
            'livewire.usertrack-leads', [
            'leads' => Address::query()
                ->userActions($this->user)
                ->periodActions($this->period)
                ->with('assignedToBranch')
                ->whereDoesntHave('opportunities')

                ->withLastActivityId()
                ->with('lastActivity')
                ->dateAdded()
                ->withCount('openOpportunities')
                ->when(
                    $this->search, function ($q) {
                        $q->search($this->search);
                    }
                )
                ->orderByColumn($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
            ]
        );
    }

    private function _setPeriod()
    {
        
        $branch = Branch::first();
        $this->period = $branch->getPeriod($this->setPeriod);
        
       


    }
}