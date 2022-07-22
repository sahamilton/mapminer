<?php

namespace App\Http\Livewire;
use App\Address;
use App\Branch;
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
    public $branch;
    public $lat;
    public $lng;
    public $myinfo;
    public $distance;
    public Address $address;
    protected $paginationTheme = 'bootstrap';
    /**
     * [updatingLat description]
     * 
     * @return [type] [description]
     */
    public function updatingLat()
    {
        $this->resetPage();
    }
    /**
     * [updatedBranchId description]
     * 
     * @return [type] [description]
     */
    public function updatedBranchId()
    {
        
        $this->_getBranch();
        $this->searchaddress = $this->branch->fullAddress();

        $this->resetPage();
    }
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
     * [updateSearch description]
     * 
     * @return [type] [description]
     */
    public function updateSearch()
    {
        $this->resetPage();
    }
    /**
     * [updatingDistance description]
     * 
     * @return [type] [description]
     */
    public function updatingDistance()
    {
        $this->resetPage();
    }
    /**
     * [updateSearchAddress description]
     * 
     * @return [type] [description]
     */
    public function updateSearchAddress()
    {
        
        $geoCode =$this->_geoCodeAddress();
        $this->lat = $geoCode->first()->getCoordinates()->getLatitude();
        $this->lng = $geoCode->first()->getCoordinates()->getLongitude();
        $this->searchaddress  = $geoCode->first()->getFormattedAddress();
        
    }
    /**
     * [sortBy description]
     * @param  [type] $field [description]
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
     * @param Array  $myinfo [description]
     * 
     * @return [type]         [description]
     */
    public function mount(Array $myinfo)
    {
        
        $this->lat = $myinfo['lat'];
        $this->lng = $myinfo['lng'];
        $this->searchaddress = $myinfo['search'];
        $this->distance = $myinfo['distance'];
        $this->myBranches = auth()->user()->person->myBranches();
        $this->branch_id = array_keys($this->myBranches)[0];
        $this->_initializeAddress();
        $this->_getBranch();
    }

    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        
        
        return view(
            'livewire.search-leads',
            [
                'leads'=>$this->_getLeads(),
                    
                'distances'=>[1=>'1 mile', 5=>'5 miles', 10=>'10 miles', 25=>'25 miles'],    
                    
            ]
        );
    }
    /**
     * [_initializeAddress description]
     * 
     * @return [type] [description]
     */
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
    /**
     * [_geoCodeAddress description]
     * 
     * @return [type] [description]
     */
    private function _geoCodeAddress()
    {
        return app('geocoder')->geocode($this->searchaddress)->get();
    }
    /**
     * [_getLeads description]
     * 
     * @return [type] [description]
     */
    private function _getLeads()
    {
        $address = $this->_initializeAddress();
        $addresses = Address::nearby($address, $this->distance)
            ->with('company')
            ->where(
                function ($q) {
                    $q->doesntHave('assignedToBranch');
                }        
            )
            ->withCount('assignedToBranch')
            ->withCount('openOpportunities')
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        $addresses->map(
            function ($address) {
                $address['type'] = $this->_getType($address);
                return $address;
            }
        );

        return $addresses;
    }
    /**
     * [_getType description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    private function _getType(Address $address) :string
    {
        
        if ($address->open_opportunities_count > 0) {
            return "opportunity";
        } elseif ($address->assigned_to_branch_count > 0) {
             return "branchlead";
        } elseif (isset($address->isCustomer)) {
            return "customer";
        } elseif ($address->assigned_to_branch_count === 0) {
            return "lead";
        } else {
            return "lead";
        }
    }
    /**
     * [_getbranch description]
     * 
     * @return [type] [description]
     */
    private  function _getbranch()
    {
        $this->branch = Branch::findOrFail($this->branch_id);
    }
}
