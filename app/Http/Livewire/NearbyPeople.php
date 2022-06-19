<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Role;
use App\Location;
use App\Person;
class NearbyPeople extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $address;
    public $distance = '25';
    public $sortField = 'lastname';
    public $sortAsc ='true';
    public $perPage =10;
    public Location $location;
    public $roletype = 9;
    public $search='';
    
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
        

        $this->_getLocation();
        return view('livewire.nearby-people',

            [
                'people'=>Person::query()
                    ->select('users.*', 'persons.*')
        
                    ->join('users', 'persons.user_id','=', 'users.id')
                    ->search($this->search)
                    ->with('branchesServiced','userdetails.roles')
                    ->when(
                        $this->distance != 'all', function ($q) {
                            $q->nearby($this->location, $this->distance);
                        }, function ($q) {
                        $q->distanceTo($this->location);
                    })->when(
                        $this->roletype != 'all', function ($q) {
                            $q->whereHas(
                                'userdetails.roles', function ($q) {
                                    $q->whereIn('roles.id', [$this->roletype]);
                                }
                            );
                        }
                    )
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                    'distances'=>['all'=>'All', 5=>5,10=>10,25=>25, 50=>50,100=>100],
                    'roles'=>$this->_getRoles(),
                   
            ]


        );
    }

    private function _getRoles()
    {
        $roleslist =Role::pluck('display_name', 'id')->toArray();
        $roles['all']='All';
        $list = array_replace($roles , $roleslist);
        asort($list);

        return $list;
    }

    private function _getLocation()
    {
        if(!$this->location || $this->address != $this->location->address) {
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
