<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Opportunity;
use App\Address;
use Livewire\WithPagination;

class AddressOpportunities extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';
    public array $owned;
    public $address_id;
   

     // opportunities

    public $status = 0;
    public $address_branch_id;
    public $branch_id;
    public $opportunityModal = false;
    public $closeOpportunityModal = false;
    public Opportunity $opportunity;
   

    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
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


    public function mount(Address $address, array $owned=null)
    {
        $this->address = $address;
        $this->owned = $owned;
        if($address = Address::with('claimedByBranch')->findOrFail($address->id)->claimedByBranch->first()) {
            $this->branch_id = $address->id; 
            $this->address_branch_id = $address->pivot->id;
        }
    }

    public function render()
    {
        return view('livewire.address-opportunities',
            [
                'opportunities'=>Opportunity::query()
                    ->where('address_id', $this->address->id)
                    ->when(
                        $this->status !='all', function ($q) {
                            $q->where('closed', $this->status);
                        }
                    )
                    ->select('opportunities.*', 'businessname')
                    ->join('addresses', 'addresses.id', '=', 'opportunities.address_id')

                    ->withLastactivity()
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'opportunityStatuses' =>['all'=>'All', 0=>'Open',1=>'Closed-Won',2=>'Closed-Lost'],
                ]
            );
    }

    /*

        Adding opportunities



    */
    public function addOpportunity(Address $address)
    {
     

        $this->resetOpportunities($address);
        $this->doShow('opportunityModal');
        @ray($this->opportunity);
        $this->address = $address;
       

    }
    
    /**
     * [resetActivities description]
     * 
     * @return [type] [description]
     */
    private function resetOpportunities(Address $address = null)
    {
        
        

        if(isset($address)) {
            $title = "Opportunity @ " . $address->businessname;
        } else {
            $title = "Opportunity @ "; 
        }
        
        $this->opportunity = Opportunity::make(
            [
                'title'=>$title,
                'expected_close' => now()->addWeek(1),
            ]
        );
 
    }

    public function closeOpportunity(Opportunity $opportunity)
    {
        $this->doShow('closeOpportunityModal');
        $this->opportunity->actual_close = @now();
        //
    }
    /**
     * [store description]
     * 
     * @return [type] [description]
     */
    public function storeOpportunity()
    {
        $this->_getOpportunity();
        $this->_recordOpportunity();
        
        $this->resetOpportunities();
        $this->doClose('opportunityModal');
    }
   
    
    /**
     * [_recordActivity description]
     * 
     * @return [type] [description]
     */
    private function _recordOpportunity()
    {
        $this->opportunity->address_id = $this->address_id;
        $this->opportunity->branch_id = $this->branch_id;   
        $this->opportunity->address_branch_id = $this->address_branch_id;
        $this->opportunity->user_id =  auth()->user()->id;
        $this->opportunity->save();
      
        
        return $opportunity;
    }
    /**
     * [_getActivity description]
     * 
     * @return [type] [description]
     */
    private function _getOpportunity() 
    {
        $rules = [

            'opportunity.title'=>'required',
            'opportunity.expected_close'=> 'date',
            'opportunity.value' => 'numeric|min:1', 
            'opportunity.requirements' => 'numeric|min:1', 
            'opportunity.duration' => 'numeric|min:1', 
            'opportunity.description'=>'required',

        ] ;   
        $this->validate($rules);
            
    }
    public function doShow($form=null)
    {
      
       $this->$form =true;
        
    }
    public function doClose($form=null)
    {
       $this->$form= false;
               
        
    }
}
