<?php

namespace App\Http\Livewire;
use App\Address;
use Livewire\Component;
use Livewire\WithPagination;

class LeadTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = '';
 

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $branches = auth()->user()->person->myBranches();
        
        return view('livewire.lead-table', [
            'leads' => Address::whereHas(
                'assignedToBranch', function ($q) use ($branches) {
                    $q->whereIn('branch_id', array_keys($branches));
                }
            )
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