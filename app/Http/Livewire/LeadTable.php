<?php

namespace App\Http\Livewire;
use App\Address;
use App\Branch;
use App\Person;
use Livewire\Component;
use Livewire\WithPagination;

class LeadTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = null;
    public $branch_id;

    public $myBranches;

 
    public function updatingSearch()
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
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    public function mount($branch, $search = null)
    {
     
        $this->branch_id = $branch;
        $person = new Person();
        $this->myBranches = $person->myBranches();
        $this->search = $search;
        
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        //$branches = auth()->user()->person->myBranches();
       
        return view(
            'livewire.lead-table', [
            'leads' => Address::query()
                ->whereIn(
                    'addresses.id', function ($query) {
                        $query->select('address_id')
                            ->from('address_branch')
                            ->where('branch_id', $this->branch_id)
                            ->where('status_id', 2);
                    }
                )
                ->search($this->search)
                ->with('assignedToBranch')
                ->whereDoesntHave('opportunities')
                ->withLastActivityId()
                ->with('lastActivity')
                ->dateAdded()
                ->withCount('openOpportunities')
                ->orderByColumn($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'branch'=>Branch::query()->with('currentcampaigns')->findOrFail($this->branch_id),
            ]
        );
    }
}
