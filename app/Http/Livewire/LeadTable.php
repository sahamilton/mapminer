<?php

namespace App\Http\Livewire;
use App\Address;
use App\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class LeadTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = '';
    public $branch;
 
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
    public function mount($branch)
    {
        $this->branch = Branch::with('currentcampaigns')->findOrFail($branch);
    }
    public function render()
    {
        //$branches = auth()->user()->person->myBranches();
        
        return view('livewire.lead-table', [
            'leads' => Address::query()
                ->search($this->search)
                ->select(['id', 'businessname', 'street', 'city','state'])
                ->whereIn(
                    'addresses.id', function ($query) {
                        $query->select('address_id')
                            ->from('address_branch')
                            ->where('branch_id', $this->branch->id)
                            ->where('status_id',2);
                    }
                )->whereDoesntHave('opportunities')
                ->withLastActivityId()
                ->with('lastActivity')
                ->withCount('openOpportunities')
                ->when(
                    $this->search, function ($q) {
                        $q->search($this->search);
                    }
                )
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]
        );
    }
}