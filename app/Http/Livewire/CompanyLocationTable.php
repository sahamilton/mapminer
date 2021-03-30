<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Address;
use App\Branch;
use App\Company;

class CompanyLocationTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'id';
    public $state='All';
    public Company $company;
    public $company_id;
    public $sortAsc = true;
    public $search ='';
    public Branch $branch;
    public $claimed='All';
   


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
    public function mount($company_id)
    {
        
        $this->company_id = $company_id;
        $this->company = Company::findOrFail($company_id);
        $this->branch = Branch::findOrFail(1506);
        
    }
    public function render()
    {
        
        return view(
            'livewire.company-location-table', [
                'locations'=>Address::query()
                    
                    ->where('company_id', $this->company_id)
                    ->when(
                        $this->state != 'All', function ($q) {
                                $q->where('state', $this->state);
                        }
                    )
                    ->when(
                        $this->claimed != 'All', function ($q) {
                            $q->when(
                                $this->claimed == 'claimed', function ($q) {
                                    $q->has('assignedToBranch');
                                }, function ($q) {
                                    $q->doesntHave('assignedToBranch');
                               
                                }
                            );
                        }
                    )
                    ->search($this->search)
                    ->with('assignedToBranch')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
            'allstates' => Address::select('state')
                ->distinct('state')
                ->select('state')
                ->where('company_id', $this->company_id)
                ->orderBy('state')
                ->pluck('state')->toArray(),
            

            ]
        );
        
    }
}
