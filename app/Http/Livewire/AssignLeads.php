<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Location;
use App\Branch;
use App\Address;
use App\Person;
class AssignLeads extends Component
{
    use WithPagination, NearbyGeocoder;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'distance';
    public $sortAsc = true; 
    
    public $distance = '25';
    public $leaddistance = '0.01';
    public $address;
    public $view = 'branch';
    public Location $location;
    public $roles = [3,7,9,13];

    
    
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
        @ray($this->location);
        return view('livewire.assignleads.assign-leads',
            [
                'branches'=>Branch::query()
                    ->with('branchteam.reportsto')
                    
                    ->nearby($this->location, $this->distance)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'people' => Person::query()
                    ->with('reportsTo','userdetails.roles', 'branchesServiced')
                    ->wherehas('userdetails.roles', function ($q) {
                        $q->whereIn('roles.id', $this->roles);
                    })
                    ->nearby($this->location, $this->distance)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'leads'=>Address::query()
                    ->with('assignedToBranch', 'company')

                    ->nearby($this->location, $this->leaddistance)
                    ->orderBy('distance', 'asc')
                    ->get(),

                'distances'=>['25'=>'25 miles', '50'=>'50 miles', '100'=>'100 miles'],
                'views' => ['branch'=>'Branches', 'people'=>'People'],
            ]
        );
    }

    

}


