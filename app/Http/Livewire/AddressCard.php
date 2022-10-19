<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Address;
use App\Models\Contact;
use App\Models\State;
use App\Models\Activity;
use App\Models\Opportunity;
use App\Models\ActivityType;
use App\Models\Campaign;
use App\Models\Branch;
use App\Jobs\TransferLeadRequestJob;


class AddressCard extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';
    public $ranked;
    public $address_id;
    public $open = true;
    public array $branches;
    
    public $view = 'summary';
    public $owned;
    public Address $location;




    public $branch_id;
    public $addressModal = false;
    public $confirmModal = false;
    public $transferModal = false;
    public $requestTransfer = false;
    public $transferbranch;
    public array $myBranches;

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
     * [updatingView description]
     * 
     * @return [type] [description]
     */
    public function updatingView()
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
     * @param int         $address_id [description]
     * @param string|null $view       [description]
     * 
     * @return [type]                  [description]
     */
    public function mount(int $address_id, string $view=null)
    {
       
        $this->address = Address::query()
            ->withCount('activities', 'contacts', 'opportunities', 'duplicates')
            ->with('claimedByBranch', 'ranking')->find($address_id);
        
        if ($this->address->claimedByBranch->count() > 0) {
            $this->ranked =$this->_getAddressRating(); 
            $this->branch_id = $this->address->claimedByBranch->first()->id;
        }
       
        $this->myBranches = $this->_getMyBranches();
       
        
        $this->branches = Branch::has('manager')->pluck('branchname', 'id')->toArray();           

        
        isset($view) ? $this->view = $view : $this->view = 'summary';
        
      
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->location = Address::findOrFail($this->address_id);
        $this->owned = $this->_checkIfOwned();

        return view(
            'livewire.address-card',
            [


                  
                
                'leadStatuses' =>[1=>'Offered',2=>'Owned', 4=>'Rejected'],
                
                'campaigns' => Campaign::active()->get(),
                'states'=>State::pluck('fullstate', 'statecode')->toArray(),
                'viewtypes'=>[
                    'summary'=>'Summary',
                    'contacts'=>'Contacts', 
                    'activities'=>'Activities', 
                    'opportunities'=>'Opportunities',
                    'duplicates'=>'Duplicates',
                    'relatedNotes'=>'Notes'],

            ]
        );
    }
    private function _getAddressRating()
    {
            $this->ranking = $this->address->claimedBybranch->first()->pivot->rating;
    }
    /**
     * [changeview description]
     * 
     * @param [type] $view [description]
     * 
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
        
        if (auth()->user()->hasRole(['branch_manager', 'staffing_specialst'])) {
            return array_intersect($this->location->claimedByBranch->pluck('id')->toArray(), $this->myBranches); 
        }
        return $owned =[];

    }
    /**
     * [_getMyBranches description]
     * 
     * @return [type] [description]
     */
    private function _getMyBranches()
    {
        return auth()->user()->person
            ->getMyBranches();
    }
    
    /**
     * [changeCustomerType description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function changeCustomerType(Address $address)
    {

        ! $address->isCustomer ?  $address->isCustomer=1 :$address->isCustomer=null;;

        $address->save();

    }
    /**
     * [updateRating description]
     * 
     * @param [type] $ranked [description]
     * 
     * @return [type]         [description]
     */
    public function updateRating($ranked)
    {
        
        $this->location->claimedByBranch()->updateExistingPivot($this->branch_id, ['rating'=>$ranked + 1]);
        $this->_getAddressRating();
    }
    
    /**
     * [editAddress description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function editAddress(Address $address)
    {
        $this->location = $address;
        $this->doShow('addressModal');

    }
    /**
     * [updateAddress description]
     * 
     * @return [type] [description]
     */
    public function updateAddress()
    {
        $this->validate();
        $geocode = app('geocoder')->geocode($this->location->fullAddress())->get();
       
        if (count($geocode) > 0) {
             $this->location->lat = $geocode->first()->getCoordinates()->getLatitude();
             $this->location->lng = $geocode->first()->getCoordinates()->getLongitude();
            
        }

        if (isset($this->location->customer_id)) {
            @ray($this->location);
            $this->location->isCustomer =1;
        }
        $this->doClose('addressModal');
        $this->location->save();
        $this->resetPage();

    }
    /**
     * [rules description]
     * 
     * @return [type] [description]
     */
    public function rules()
    {
        return [
            'location.businessname'=>'required',
            'location.street'=>'required',
            'location.city'=>'required',
            'location.state'=>'required',
            'location.zip'=>'required',
            'location.phone'=>'sometimes',
            'location.customer_id'=>'sometimes',
            'location.isCustomer'=>'sometimes',

        ];
    }
    /**
     * [deleteAddress description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function deleteAddress(Address $address)
    {
        $address->loadCount('openOpportunities');
        if ($address->open_opportunities_count > 0) {
            session()->flash('error', 'You must close all open opportunities before removing this lead from your branch');
        } else {
            $this->doShow('confirmModal');
        }
        
    }
    /**
     * [destroyAddress description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function destroyAddress(Address $address)
    {
       
        $address = Address::with('claimedByBranch')->findOrFail($address->id);
        
        $address->claimedByBranch()->detach();
        $this->owned = $this->_checkIfOwned();
        //$this->address = null;
        $this->doClose('confirmModal');

    }
    /**
     * [claimLead description]
     * 
     * @param Branch  $branch  [description]
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function claimLead(Branch $branch, Address $address)
    {
        
         $address->claimedByBranch()->attach($branch->id, ['status_id'=>2]);
         $this->ranking = 0;
         $this->owned = $this->_checkIfOwned();


    }
    /**
     * [requestTransfer description]
     * 
     * @return [type] [description]
     */
    public function requestTransfer()
    {
       
        $this->doShow('requestTransfer'); 
    }
    /**
     * [processTransferRequest description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function processTransferRequest(Address $address)
    {
        TransferLeadRequestJob::dispatch($address, auth()->user());
            session()->flash('success', 'An email has been sent to the owning branch requesting that the lead be transferred');
        $this->doClose('requestTransfer');
    }
    /**
     * [reassignAddress description]
     * 
     * @return [type] [description]
     */
    public function reassignAddress()
    {
       
        $this->doShow('transferModal');
    }
    /**
     * [transferLead description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    public function transferLead(Address $address)
    {
        
        $fromBranch = $address->claimedByBranch()->first()->branchname;
        $pivot['status_id']= 2;
        $pivot['comments'] = 'Transferred from branch '. $fromBranch .' to ' . $this->transferbranch . " on " . now()->format('Y-m-d');
        $this->owned = [];
        $address->claimedByBranch()->detach();
        $address->claimedByBranch()->attach($this->transferbranch, $pivot);
        $this->doClose('transferModal');
    }
    /**
     * [doClose description]
     * 
     * @param [type] $form [description]
     * 
     * @return [type]       [description]
     */
    public function doClose($form)
    {
        $this->$form = false;
    }
    /**
     * [doShow description]
     * 
     * @param [type] $form [description]
     * 
     * @return [type]       [description]
     */
    public function doShow($form)
    {
        $this->$form = true;
    }
    
    
}