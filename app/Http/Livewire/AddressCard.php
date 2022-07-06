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
use App\Branch;
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
       
        
        $this->branches = Branch::has('manager')->pluck('branchname', 'id')->toArray();           
        
        
        isset($view) ? $this->view = $view : $this->view = 'summary';
        
      
    }
    public function render()
    {
        $this->location = Address::findOrFail($this->address_id);
        $this->owned = $this->_checkIfOwned();
        @ray($owned);
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
        
        if(auth()->user()->hasRole(['branch_manager', 'staffing_specialst'])) {
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
    

    public function editAddress(Address $address)
    {
        $this->location = $address;
        $this->doShow('addressModal');

    }

    public function updateAddress()
    {
        $this->validate();
        $geocode = app('geocoder')->geocode($this->location->fullAddress())->get();
       
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

    public function deleteAddress(Address $address)
    {
        $address->loadCount('openOpportunities');
        if ($address->open_opportunities_count > 0) {
            session()->flash('error', 'You must close all open opportunities before removing this lead from your branch');
        } else {
            $this->doShow('confirmModal');
        }
        
    }
    public function destroyAddress(Address $address)
    {
       
        $address = Address::with('claimedByBranch')->findOrFail($address->id);
        
        $address->claimedByBranch()->detach();
        $this->owned = $this->_checkIfOwned();
        //$this->address = null;
        $this->doClose('confirmModal');

    }

    public function claimLead(Branch $branch, Address $address)
    {
        
         $address->claimedByBranch()->attach($branch->id, ['status_id'=>2]);
         $this->owned = $this->_checkIfOwned();
         @ray($this->owned, $branch->id);

    }
    public function requestTransfer()
    {
       

       $this->doShow('requestTransfer'); 
    }
    public function processTransferRequest(Address $address)
    {
        TransferLeadRequestJob::dispatch($address, auth()->user());
            session()->flash('success', 'An email has been sent to the owning branch requesting that the lead be transferred');
        $this->doClose('requestTransfer');
    }
    public function reassignAddress()
    {
       
        $this->doShow('transferModal');
    }
    public function transferLead(Address $address)
    {
        
        $fromBranch = $address->claimedByBranch()->first()->branchname;
        $pivot['status_id']= 2;
        $pivot['comments'] = 'Transferred from branch '. $fromBranch .' to ' . $this->transferbranch . " on " . now()->format('Y-m-d');
        $this->owned = [];
        $address->claimedByBranch()->detach();
        $address->claimedByBranch()->attach($this->transferbranch,$pivot);
        $this->doClose('transferModal');
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
