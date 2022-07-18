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
    
    use WithPagination, NearbyGeocoder;

    public $perPage = 10;
    public $sortField = 'companyname';
    public $sortAsc = true;
    public $search = '';
    public $location;
    public String $address;
    public Array $verticalGroup;
    public $vertical = 'all';
    public $distance = '25';
    public $accounttype='all';
    public $paginationTheme = 'bootstrap';
    
    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    /**
     * [updatingAddress description]
     * 
     * @return [type] [description]
     */
    public function updatingAddress()
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
    /**
     * [mount description]
     * 
     * @param [type] $accounttype [description]
     * 
     * @return [type]              [description]
     */
    public function mount($accounttype=null)
    {
        
        
        $this->address = auth()->user()->person->fullAddress();
        $this->location = auth()->user()->person;
        if ($accounttype) {
            $this->accounttype=$accounttype;
        }
        $this->_getVerticalGroup();
        
        
    }

    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->updateAddress();
        $this->_getVerticalGroup();
        return view(
            'livewire.company-table', [
            'companies' => Company::query()
                
                ->search($this->search)
                ->with('managedBy.userdetails', 'industryVertical', 'serviceline', 'type')
                ->with(
                    'locations', function ($q) {
                        $q->when(
                            $this->distance != 'any', function ($q) {
                                $q->nearby($this->location, $this->distance);
                            }
                        );
                            
                    }
                )
                ->when(
                    $this->distance != 'any', function ($q) {
               
                        $q->whereHas(
                            'locations', function ($q) {
                                $q->nearby($this->location, $this->distance);
                            }
                        );

                    }
                )
                ->when(
                    $this->vertical != 'all', function ($q) {
                        @ray($this->verticalGroup);
                        $q->whereHas(
                            'industryVertical', function ($q) {
                                $q->where('searchfilters.lft', '>=', $this->verticalGroup['lft'])
                                    ->where('searchfilters.rgt', '<=', $this->verticalGroup['rgt']);
                            }
                        );

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
                
                'types'=>AccountType::orderBy('type')
                    ->pluck('type', 'id')
                    ->prepend('All', 'all')
                    ->toArray(),

                'verticals' => SearchFilter::industries()
                    ->where('depth', '>', 1)
                    ->orderBy('filter')
                    ->pluck('filter', 'id')
                    ->prepend('All', 'all')
                    ->toArray()
            ]
        );
    }

    /**
     * [setType description]
     * 
     * @param [type] $type [description]
     *
     * @return [type] [<description>]
     */
    public function setType($type)
    {
        $this->accounttype=$type;
    }
    /**
     * [_getVertical description]
     * 
     * @return [type] [description]
     */
    private function _getVerticalGroup()
    {
        if ($this->vertical != 'all') {
            $vertical = SearchFilter::industries()
                ->where('depth', '>', 1)
                ->orderBy('filter')
                
                ->findOrFail($this->vertical);
           
        } else {
            $vertical = Searchfilter::industries()
                ->where('depth', 1)
                ->first();
        }

        $this->verticalGroup['lft'] = $vertical->lft;
        $this->verticalGroup['rgt'] = $vertical->rgt;
    }
}
