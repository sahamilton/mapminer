<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Address;

class CompanyLocationTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'id';
    public $state='All';
    public $company;
    public $sortAsc = true;
    public $search ='';
   


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
    public function mount($company)
    {
        $this->company = $company;
        
    }
    public function render()
    {
        
        return view(
            'livewire.company-location-table', [
                'locations'=>Address::query()
                    ->where('company_id', $this->company)
                    ->when(
                        $this->state != 'All', function ($q) {
                                $q->where('state', $this->state);
                        }
                    )
                ->search($this->search)
                ->with('assignedToBranch')
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            'allstates' => Address::select('state')
                ->distinct('state')
                ->where('company_id', $this->company)
                ->orderBy('state')
                ->get(),
            

            ]
        );
        
    }
}
