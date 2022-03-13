<?php

namespace App\Http\Livewire;
use App\Address;
use Livewire\Component;
use Livewire\WithPagination;
use App\Transformers\AddressMapTransformer;

class SearchLeads extends Component
{
    
    use WithPagination;
    
    public $searchaddress;
    public $search='';
    public $sortField = 'distance';
    public $sortAsc = true;
    public $perPage=10;
    public $branch_id;
    public $myBranches;
    public $lat;
    public $lng;
    public $myinfo;
    public $distance;
    public Address $address;
    protected $paginationTheme = 'bootstrap';

    public function updatedLat()
    {
        $this->resetPage();
    }
    public function updatedBranchId()
    {
        $this->resetPage();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedDistance()
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

    public function mount(Array $myinfo)
    {
        
        $this->lat = $myinfo['lat'];
        $this->lng = $myinfo['lng'];
        $this->searchaddress = $myinfo['search'];
        $this->distance = $myinfo['distance'];
        $this->myBranches = auth()->user()->person->myBranches();
        $this->branch_id = array_keys($this->myBranches)[0];
        $this->_initializeAddress();
    }


    public function render()
    {
        
        
        return view('livewire.search-leads',
            [
                'leads'=>$this->_getLeads(),
                    
                'distances'=>[1=>'1 mile', 5=>'5 miles', 10=>'10 miles', 25=>'25 miles'],    
                    
            ]
            
        );
    }
    public function updateSearchaddress()
    {
        
        $geoCode =$this->_geoCodeAddress();
        $this->lat = $geoCode->first()->getCoordinates()->getLatitude();
        $this->lng = $geoCode->first()->getCoordinates()->getLongitude();
        $this->searchaddress  = $geoCode->first()->getFormattedAddress();
        
    }
    private function _initializeAddress()
    {

       
        return $this->address = Address::make(
            [
               'searchaddress'=>$this->searchaddress,
               'lat'=> $this->lat,
               'lng' => $this->lng,
            ]
        );
 
    }

    private function _geoCodeAddress()
    {
        return app('geocoder')->geocode($this->searchaddress)->get();
    }

    private function _getLeads()
    {
        $address = $this->_initializeAddress();
        $addresses = Address::nearby($address, $this->distance)
            ->with('company')
            ->where(function ($q) {
                $q->doesntHave('assignedToBranch')
                        ->orWhereHas('assignedToBranch', function ($q) {
                            $q->where('branches.id', $this->branch_id);
                        }
                    );
                }
            )
            ->withCount('assignedToBranch')
            ->withCount('openOpportunities')
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        $addresses->map(function ($address)
        {
            $address['type'] = $this->_getType($address);
            return $address;
        });

       return $addresses;
    }

    private function _getType(Address $address) :string
    {
        
        if($address->open_opportunities_count > 0) {
            return "opportunity";
        } elseif ($address->assigned_to_branch_count > 0){
             return "branchlead";
        } elseif (isset($address->isCustomer)){
            return "customer";
        } elseif ($address->assigned_to_branch_count === 0){
            return "lead";
        } else {
            return "lead";
        }
    }
}
