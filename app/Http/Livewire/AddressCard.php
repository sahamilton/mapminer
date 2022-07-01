<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Address;
use App\Contact;
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
    public Address $address;

    public $paginationTheme = 'bootstrap';
    public $view = 'summary';
    public $owned = false;
    
    public $branch_id;
    
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
    public function mount(Address $address, string $view=null)
    {
       

        $this->address = $address->load('claimedByBranch');
        dd($this->address);
                    
        $this->myBranches = $this->_getMyBranches();
        $this->owned = $this->_checkIfOwned();
        
        isset($view) ? $this->view = $view : $this->view = 'summary';
        if($this->address->claimedByBranch->first()) {
            $this->branch_id = $address->id; 
            $this->address_branch_id = $this->address->claimedByBranch->first()->pivot->id;
            $this->ranking = $this->address->getMyRanking();
        }
      
    }
    public function render()
    {
        @ray($this->address);
        return view(
            'livewire.address-card',
            [


                'address' =>Address::withCount('activities', 'contacts', 'opportunities')
                    ->with('claimedByBranch', 'ranking')->find($this->address->id),  
                
                'leadStatuses' =>[1=>'Offered',2=>'Owned', 4=>'Rejected'],
                
                'campaigns' => Campaign::active()->get(),
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
    public function changeview(string $view)
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
    

    
    
}
