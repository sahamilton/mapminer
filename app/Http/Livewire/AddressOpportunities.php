<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Opportunity;
use App\Address;
use Livewire\WithPagination;
use Carbon\Carbon;

class AddressOpportunities extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';
    public array $owned;
    public $address_id;
    public Address $address;

     // opportunities

    public $status = 'all';
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
        if(count($this->getErrorBag()->all()) > 0){
             @ray($this->getErrorBag()->all());
             $this->emit('error:example');

        }
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
    public function addOpportunity()
    {
     

        $this->resetOpportunities();
        $this->doShow('opportunityModal');

       

    }
    
    /**
     * [resetActivities description]
     * 
     * @return [type] [description]
     */
    private function resetOpportunities()
    {
        
        

        if(isset($this->address)) {
            $title = "Opportunity @ " . $this->address->businessname;
        } else {
            $title = "Opportunity @ "; 
        }
        
        $this->opportunity = Opportunity::make(
            [
                'title'=>$title,
                'expected_close'=>now()->addWeek(2),
                'address_id'=> $this->address->id,
                'branch_id' => $this->branch_id,   
                'address_branch_id' => $this->address_branch_id,
                'user_id' => auth()->user()->id,
                'closed' => 0,
                
            ]
        );
        @ray($this->opportunity);
 
    }

    public function editOpportunity(Opportunity $opportunity, $action)
    {
       if ($action === 'close') {

            $this->doShow('closeOpportunityModal');
            $this->opportunity = $opportunity;
            $this->opportunity->closed = null;
            $this->opportunity->actual_close = @now()->format('Y-m-d');
            $this->opportunity->comments = null;
       }
       
        //
    }
    /**
     * [store description]
     * 
     * @return [type] [description]
     */
    public function storeOpportunity()
    {
        @ray($this->opportunity);
        $this->validate();
        $this->_recordOpportunity();
        
        $this->resetOpportunities();
        $this->doClose('closeOpportunityModal');
    }
   
    
    /**
     * [_recordActivity description]
     * 
     * @return [type] [description]
     */
    private function _recordOpportunity()
    {
        
       
        $this->opportunity->expected_close = Carbon::parse($this->opportunity->expected_close);
        @ray($this->opportunity);
        $this->opportunity->save();

    }

    public function rules()
    {
        


        return [

            'opportunity.title'=>'required',
            'opportunity.expected_close'=> 'required|date',
            'opportunity.comments'=>'sometimes',
            
            'opportunity.closed'=>'in:0,1,2',
            'opportunity.value' => 'numeric|min:1', 
            'opportunity.requirements' => 'numeric|min:1', 
            'opportunity.duration' => 'numeric|min:1', 
            'opportunity.description'=>'required',
            'opportunity.Top25'=>'sometimes',
            'opportunity.csp'=>'sometimes',
            'opportunity.address_id'=>'required',
            'opportunity.branch_id'=>'required',
            'opportunity.address_branch_id'=>'required',
            'opportunity.actual_close' => 'sometimes|nullable|date|before:tomorrow',
            'opportunity.user_id'=>'required',

        ] ;
    }
    public function closeOpportunity(Opportunity $opportunity)
    {
        @ray('closing', $this->opportunity);
        $this->validate(['opportunity.actual_close' => 'required|date|before:tomorrow', 'opportunity.comments'=>'required|filled', 'opportunity.closed'=>'required|in:1,2']);
        
        $this->doClose('closeOpportunityModal');
        
        $this->opportunity->update(['actual_close' => Carbon::parse($this->opportunity->actual_close)]);

    }
    public function doShow($form=null)
    {
      
       $this->$form =true;
        
    }
    public function doClose($form=null)
    {
       $this->$form= false;
               
        
    }

    /**
     * [changeCustomerType description]
     * 
     * @param  Address $address [description]
     * @return [type]           [description]
     */
    public function changeOpportunityType(Opportunity $opportunity, $type)
    {

        ! $opportunity->$type ?  $opportunity->$type=1 : $opportunity->$type=null;;

        $opportunity->save();
        @ray($opportunity);
    }
}
