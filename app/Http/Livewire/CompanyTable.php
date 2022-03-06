<?php

namespace App\Http\Livewire;
use App\Model;
use App\Company;
use App\Person;
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
    public $location;
    public String $address;
    public $types;
    public $distance = '25';
    public $accounttype=false;
    public $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAddress()
    {
        $this->_geoCodeAddress();
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
        $this->types = AccountType::pluck('type', 'id')->toArray();
        $this->address = auth()->user()->person->fullAddress();
        $this->location = auth()->user()->person;
        
    }


    public function render()
    {
        $this->_geoCodeAddress();
        return view(
            'livewire.company-table', [
            'companies' => Company::query()
                
                ->search($this->search)
                ->with('managedBy.userdetails', 'industryVertical', 'serviceline', 'type')
                ->whereHas('locations', function ($q) {
                    $q->nearby(auth()->user()->person, $this->distance);
                })
                ->with(
                   
                        'locations', function ($q) {
                            $q->nearby(auth()->user()->person, $this->distance);
                        }
                    
                )
                ->when(
                    $this->accounttype && $this->accounttype != 'All', function ($q) {
                        $q->whereHas(
                            'type', function ($q) {
                                $q->where('accounttypes.id', $this->accounttype);
                            }
                        );
                    }
                )
           
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'distances' => [5=>5,10=>10, 25=>25],
            ]
        );
    }

    private function _geoCodeAddress()
    {

    }
}
