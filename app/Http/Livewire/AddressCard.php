<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Address;
use App\Contact;
use App\State;
use App\Activity;
use App\Opportunity;
use App\ActivityType;
use App\Campaign;

class AddressCard extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';
    public $ranked;
    public $address_id;
    public $open = true;

    public $paginationTheme = 'bootstrap';
    public $view = 'summary';
    public $owned = false;
    public Address $location;
    public $branch_id;
    public $addressModal=false;
    public array $myBranches;
  

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingView()
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
    public function mount(int $address_id, string $view=null)
    {
       

        
        $this->myBranches = $this->_getMyBranches();
        
        $this->owned = array_intersect(Address::findOrFail($address_id)->claimedByBranch->pluck('id')->toArray(), $this->myBranches);
                    
        $this->location = Address::findOrFail($address_id);
        
        isset($view) ? $this->view = $view : $this->view = 'summary';
        
      
    }
    public function render()
    {
        
        return view(
            'livewire.address-card',
            [


                'address' =>Address::query()
                    ->withCount('activities', 'contacts', 'opportunities')
                    ->with('claimedByBranch', 'ranking')->find($this->address_id),  
                
                'leadStatuses' =>[1=>'Offered',2=>'Owned', 4=>'Rejected'],
                
                'campaigns' => Campaign::active()->get(),
                'states'=>State::pluck('fullstate', 'statecode')->toArray(),
                'viewtypes'=>[
                    'summary'=>'Summary',
                    'contacts'=>'Contacts', 
                    'activities'=>'Activities', 
                    'opportunities'=>'Opportunities'],

            ]
        );
    }
    
    /**
     * [changeview description]
     * 
     * @param  [type] $view [description]
     * @return [type]       [description]
     */
    public function changeView(string $view)
    {
        $this->view = $view;
       
    }
    /**
     * [_checkIfOwned description]
     * 
     * @return [type] [description]
     */
    private function _checkIfOwned()
    {
        
        
        return array_intersect($this->address->claimedByBranch->pluck('id')->toArray(), $this->myBranches);

    }
    /**
     * [_getMyBranches description]
     * 
     * @return [type] [description]
     */
    private function _getMyBranches()
    {
        return auth()->user()->person
            ->branchesManaged()
            ->pluck('id')
            ->toArray();
    }
    
    /**
     * [changeCustomerType description]
     * 
     * @param  Address $address [description]
     * @return [type]           [description]
     */
    public function changeCustomerType(Address $address)
    {

        ! $address->isCustomer ?  $address->isCustomer=1 :$address->isCustomer=null;;

        $address->save();

    }
    
    public function updateRating($ranked)
    {
        
        $this->address->ranking()->sync(auth()->user()->person->id, ['ranking'=>$ranked]);
    }
    

    public function editAddress()
    {
        $this->location = Address::findOrFail($this->address_id);
        $this->doShow('addressModal');

    }

    public function updateAddress()
    {
        $this->validate();
        $geocode = app('geocoder')->geocode($this->location->fullAddress())->get();
        @ray($geocode);
            if(count($geocode) > 0) {
                 $this->location->lat = $geocode->first()->getCoordinates()->getLatitude();
                 $this->location->lng = $geocode->first()->getCoordinates()->getLongitude();
                
            }
        $this->doClose('addressModal');
        $this->location->save();
        $this->resetPage();

    }
    public function rules()
    {
        return [
            'location.businessname'=>'required',
            'location.street'=>'required',
            'location.city'=>'required',
            'location.state'=>'required',
            'location.zip'=>'required',

        ];
    }
    public function doClose($form)
    {
        $this->$form = false;
    }
    public function doShow($form)
    {
        $this->$form = true;
    }
    
    
}   
