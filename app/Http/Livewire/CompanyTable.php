<?php

namespace App\Http\Livewire;
use App\Company;
use App\AccountType;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyTable extends Component
{
    
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'companyname';
    public $sortAsc = true;
    public $search = '';
    public $types;
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
        $this->types = AccountType::all();
    }


    public function render()
    {
        return view('livewire.company-table', [
            'companies' => Company::query()
                ->search($this->search)
                ->with('managedBy.userdetails', 'industryVertical', 'serviceline', 'type')
                ->withCount('locations')
                ->when(
                    $this->accounttype && $this->accounttype != 'All', function ($q){
                        $q->whereHas('type', function($q) {
                            $q->where('accounttypes.id', $this->accounttype);
                        }
                    );
                }
            )
           
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]
        );
    }
}
