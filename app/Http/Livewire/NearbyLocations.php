<?php

namespace App\Http\Livewire;
use App\Location;
use Livewire\Component;
use App\Address;
use App\AccountType;
use App\Company;
use Livewire\WithPagination;
use App\Exports\ExportNearbyLocations;
use Excel;

    
class NearbyLocations extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public Location $location;
    public $company_ids=[];
    public $address;
    public $distance = 25;
    public $sortField = 'distance';
    public $sortAsc ='true';
    public $perPage =10;
    public $accounttype = 0;



    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAccountype()
    {
        $this->$company_ids[0] = 'All';
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
        $geocode = new Location;
        $this->location = $geocode->getMyPosition();
        $this->address = $this->location->address;
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->updateAddress();

        return view(
            'livewire.companies.nearby-locations', [
                'locations'=>Address::has('company')
                    ->when(
                        count($this->company_ids) && $this->company_ids[0] != 'All', function ($q) {
                            $q->whereIn('company_id', $this->company_ids);
                        }
                    )
                    ->when(
                        $this->accounttype != '0', function ($q) {
                            $q->whereHas(
                                'company', function ($q) {
                                    $q->where('accounttypes_id', $this->accounttype);
                                }
                            );
                        }
                    )    
                    ->nearby($this->location, $this->distance)
                    ->with('company', 'assignedToBranch')
                    ->withCount('contacts')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'accounttypes'=>$this->_getaccountTypes(),
                'companies'=>Company::when(
                    $this->accounttype != '0', function ($q) {

                        $q->where('companies.accounttypes_id', $this->accounttype);
                    }
                )->whereHas(
                    'locations', function ($q) {
                        $q->nearby($this->location, $this->distance);
                    }
                )
                ->orderBy('companyname')->pluck('companyname', 'id')->toArray(),
                'distances'=>['5','10','25', '50','100'],
            ]
        );
    }
    /**
     * [updateAddress description]
     * 
     * @return [type] [description]
     */
    public function updateAddress()
    {
        if ($this->address != $this->location->address) {
            $geocode = app('geocoder')->geocode($this->address)->get();
            
            $this->location->lat = $geocode->first()->getCoordinates()->getLatitude();
            $this->location->lng = $geocode->first()->getCoordinates()->getLongitude();
            $this->location->address = $this->address;
        }
    }

    public function export()
    {
        $this->updateAddress();
        ray($this->company_ids);
        return Excel::download(
            new ExportNearbyLocations(
                $this->location, 
                $this->distance,
                $this->accounttype,
                $this->company_ids
            ), 'nearbylocations.csv'
        );
    }

    private function _getaccountTypes()
    {
        return AccountType::orderBy('type')->pluck('type', 'id')->toArray();
        
    }
}
