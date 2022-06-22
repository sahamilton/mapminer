<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Branch;
use App\Region;
use App\Location;

class BranchTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'id';
    public $state='All';
    public $region='All';
    public $distance = '25';
    public $sortAsc = true;
    public $search ='';
    public $serviceline = 'All';
    public $userServiceLines;
    public $paginationTheme = 'bootstrap';
    public $manager = 'All';
    public Location $location;
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingDistance()
    {
        if ($this->distance === 'all') {
            $this->state='All';
        }
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

    public function mount($state = null)
    {
        if (isset($state)) {
            $this->state = $state;
            $this->distance = 'all';
        }
        $this->userServiceLines = auth()->user()->currentServiceLineIds();
        $this->_geoCodeHomeAddress();
    }
    public function render()
    {
        $this->_getLocation();
        return view(
            'livewire.branch-table', [
                'branches'=>Branch::query()
                    ->with(
                        'region', 
                        'manager', 
                        'relatedPeople.userdetails.roles', 
                        'servicelines'
                    )
                    ->when(
                        $this->manager != 'All', function ($q) {
                            $q->when(
                                $this->manager == 'with', function ($q) {
                                    $q->whereHas('manager');
                                }, function ($q) {
                                    $q->whereDoesntHave('manager');
                                }
                            );
                            
                        }
                    )
                    ->when(
                        $this->state != 'All', function ($q) {
                                $q->where('state', $this->state);
                        }
                    )
                    ->when(
                        $this->region != 'All', function ($q) {
                            $q->where('region_id', $this->region);
                        }
                    )
                    ->when(
                        $this->serviceline != 'All', function ($q) {
                            $q->whereHas(
                                'servicelines', function ($q) {
                                    $q->where('serviceline_id', $this->serviceline);

                                }
                            );
                        }, function ($q) {
                            $q->whereHas(
                                'servicelines', function ($q) {
                                    $q->whereIn('serviceline_id', array_keys($this->userServiceLines));

                                }
                            );
                        }
                    )
                    ->when(
                        $this->distance != 'all', function ($q) {
                            $q->nearby($this->location, $this->distance);
                        }, function ($q) {
                            $q->distanceTo($this->location);
                        }
                    )
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
            'allstates' => Branch::select('state')
                ->distinct('state')
                
                ->when(
                    $this->region != 'All', function ($q) {
                            $q->where('region_id', $this->region);
                    }
                )
                ->orderBy('state')
                ->get(),
            'regions' => Region::select('id', 'region')->has('branches')->orderBy('region')->get(),
            'distances'=>['all'=>'All', 5=>5,10=>10,25=>25, 50=>50,100=>100],
               

            ]
        );
        
    }
    
    private function _geoCodeHomeAddress()
    {
        $geocode = new Location;
        $this->location = $geocode->getMyPosition(); 
        $this->address = $this->location->address; 
    }
    

    private function _getLocation()
    {
        if(! $this->address) {
           $this->_geoCodeHomeAddress();
        }
        if(! $this->location || $this->address != $this->location->address) {
            $this->updateAddress();
        }
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
            //update session
            session()->put('geo', ['lat'=>$this->location->lat, 'lng'=>$this->location->lng, 'fulladdress'=>$this->location->address]);
        }
    }
}
