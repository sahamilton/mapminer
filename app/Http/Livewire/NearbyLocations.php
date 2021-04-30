<?php

namespace App\Http\Livewire;
use App\Location;
use Livewire\Component;
use App\Address;
use App\Company;
use Livewire\WithPagination;
use Excel;

    
class NearbyLocations extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public Location $location;
    public $company_ids=[];
    public $address;
    public $distance = 25;
    public $sortField = 'businessname';
    public $sortAsc ='true';
    public $perPage =10;





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
        $geocode = new Location;
        $this->location = $geocode->getMyPosition();
        $this->address = $this->location->address;
    }
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
                    
                    ->with('assignedToBranch')
                    ->nearby($this->location, $this->distance)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'companies'=>Company::whereHas(
                    'locations', function ($q) {
                        $q->nearby($this->location, $this->distance);
                    }
                )->orderBy('companyname')->pluck('companyname', 'id')->toArray(),
                'distances'=>['5','10','25', '50','100'],
            ]
        );
    }

    public function updateAddress()
    {
        if ($this->address != $this->location->address) {
            $geocode = app('geocoder')->geocode($this->address)->get();
            
            $this->location->lat = $geocode->first()->getCoordinates()->getLatitude();
            $this->location->lng = $geocode->first()->getCoordinates()->getLongitude();
        }
    }
}
