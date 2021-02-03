<?php

namespace App\Http\Livewire;
use App\AddressBranch;
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
    public $lead_source_id = 'All';

    public $myBranches;

 
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingLeadSourceId()
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
        
        $this->_getLeadSources();
        return view(
            'livewire.lead-table', [
            'leads' => AddressBranch::query()
                ->search($this->search)
                
                ->whereIn(
                    'addresses.id', function ($query) {
                        $query->select('address_id')
                            ->from('address_branch')
                            ->where('branch_id', $this->branch_id)
                            ->where('status_id', 2);
                    }
                )
                ->whereDoesntHave('opportunities')
                ->withLastActivityId()
                ->with('lastActivity')
                ->dateAdded()
                

                ->orderByColumn($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),

                'branch' => Branch::findOrFail($this->branch_id),
                
            ]
        );
    }

    private function _getLeadSources()
    {
        $branch = Branch::query()
            
            ->with('locations.leadsource')
            ->find($this->branch_id);
        $sources = [];
        
        foreach ($branch->locations as $location) {
            
            if (! array_key_exists($location->lead_source_id, $sources)) {
                
                $sources[$location->lead_source_id] = $location->leadsource->source;
            }
        }
        
        return $sources;
    }
}
