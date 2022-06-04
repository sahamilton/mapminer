<?php

namespace App\Http\Livewire;
use App\Model;
use App\Company;
use App\Person;
use App\AccountType;
use App\SearchFilter;
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
    public array $types;
    public array $verticals;
    public $vertical = 'all';
    public $distance = '25';
    public $accounttype='all';
    public $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAddress()
    {
        $this->location = $this->_geoCodeAddress();
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
    public function mount($accounttype=null)
    {
        
        $types = AccountType::pluck('type', 'id')->toArray();
        $types['all']='All';
        asort($types);
       
        $this->types = $types;
        $this->address = auth()->user()->person->fullAddress();
        $this->location = auth()->user()->person;
        if($accounttype){
            $this->accounttype=$accounttype;
        }
        $verticals = SearchFilter::industries()->orderBy('filter')->pluck('filter','id')->toArray();

        $verticals['all']=' All';
        asort($verticals);
       
        $this->verticals = $verticals;
        
    }


    public function render()
    {
        $this->_geoCodeAddress();
        return view(
            'livewire.company-table', [
            'companies' => Company::query()
                
                ->search($this->search)
                ->with('managedBy.userdetails', 'industryVertical', 'serviceline', 'type')
                ->with('locations', function ($q) {
                    $q->when(
                        $this->distance != 'any', function ($q) {
                            $q->nearby($this->location, $this->distance);
                            }
                        );
                        
                    }
                )
                ->when($this->distance != 'any', function ($q) {
               
                        $q->whereHas('locations', function ($q) {
                            $q->nearby($this->location, $this->distance);
                        });

                    }
                )
                ->when($this->vertical != 'all', function ($q) {
               
                        $q->whereHas('industryVertical', function ($q) {
                            $q->where('searchfilters.id', $this->vertical);
                        });

                    }
                )
                ->when(
                    $this->accounttype && $this->accounttype != 'all', function ($q) {
                        $q->whereHas(
                            'type', function ($q) {
                                $q->where('accounttypes.id', $this->accounttype);
                            }
                        );
                    }
                )
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'distances' => ['any'=>'Any',5=>5,10=>10, 25=>25],
            ]
        );
    }

    private function _geoCodeAddress()
    {

    }

    public function setType($type)
    {
        $this->accounttype=$type;
    }
}
