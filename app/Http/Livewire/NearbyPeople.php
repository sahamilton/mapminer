<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Role;
use App\Location;
use App\Person;
class NearbyPeople extends Component
{
    use WithPagination, NearbyGeocoder;
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
        $this->distance = 'all';
        $this->roletype= 'all';
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
       
        $this->_geoCodeHomeAddress();
        

        
    }
        
    public function render()
    {
        

        $this->updateAddress();
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
                    'roles'=>Role::pluck('display_name', 'id')->prepend('All','all')->toArray(),
                   
            ]


        );
    }
    
   

   
}
