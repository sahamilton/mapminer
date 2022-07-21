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
    public $type = 'all';
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
     * @param Address    $address [description]
     * @param array|null $owned   [description]
     * 
     * @return [type]              [description]
     */
    public function mount(Address $address, array $owned=null)
    {
        $this->address = $address;
        $this->owned = $owned;
        if ($address = Address::with('claimedByBranch')->findOrFail($address->id)->claimedByBranch->first()) {
            $this->branch_id = $address->id; 
            $this->address_branch_id = $address->pivot->id;
        }
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
       
        return view(
            'livewire.address-opportunities',
            [
                'opportunities'=>Opportunity::query()
                    ->where('address_id', $this->address->id)
                    ->when(
                        $this->status !='all', function ($q) {
                            $q->where('closed', $this->status);
                        }
                    )
                    ->when(
                        $this->type != 'all', function ($q) {
                            $q->when(
                                $this->type=='top25', function ($q) {
                                    $q->where('top25', 1);
                                }
                            )->when(
                                $this->type=='csp', function ($q) {
                                    $q->where('csp', 1);
                                }
                            );
                        }
                    )
                    ->select('opportunities.*', 'businessname')
                    ->join('addresses', 'addresses.id', '=', 'opportunities.address_id')

                    ->withLastactivity()
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'opportunityStatuses' =>['all'=>'All', 0=>'Open',1=>'Closed-Won',2=>'Closed-Lost'],
                'types'=>['all'=>'All', 'top25'=>'Top 25', 'csp'=>'CSP'],
            ]
        );
    }

    /**
     * [addOpportunity description]
     *
     * @return null 
     */
    public function addOpportunity()
    {
     

        $this->_resetOpportunities();
        $this->doShow('opportunityModal');

       

    }
    
    /**
     * [resetActivities description]
     * 
     * @return [type] [description]
     */
    private function _resetOpportunities()
    {
       
        if (isset($this->address)) {
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
        
 
    }
    /**
     * [editOpportunity description]
     * 
     * @param Opportunity $opportunity [description]
     * @param [type]      $action      [description]
     * 
     * @return [type]                   [description]
     */
    public function editOpportunity(Opportunity $opportunity, $action)
    {
        if ($action === 'close') {

            $this->doShow('closeOpportunityModal');
            $this->opportunity = $opportunity;
            $this->opportunity->closed = null;
            $this->opportunity->actual_close = @now()->format('Y-m-d');
            $this->opportunity->comments = null;
        }
       
    }
    /**
     * [store description]
     * 
     * @return [type] [description]
     */
    public function storeOpportunity()
    {
       
        $this->validate();
        $this->_recordOpportunity();
        
        $this->_resetOpportunities();

        $this->doClose('opportunityModal');
    }
   
    
    /**
     * [_recordActivity description]
     * 
     * @return [type] [description]
     */
    private function _recordOpportunity()
    {
        
       
        $this->opportunity->expected_close = Carbon::parse($this->opportunity->expected_close);
        
        $this->opportunity->save();

    }
    /**
     * [rules description]
     * 
     * @return [type] [description]
     */
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
    /**
     * [closeOpportunity description]
     * 
     * @param Opportunity $opportunity [description]
     * 
     * @return [type]                   [description]
     */
    public function closeOpportunity(Opportunity $opportunity)
    {
        @ray('closing', $this->opportunity);
        $this->validate(['opportunity.actual_close' => 'required|date|before:tomorrow', 'opportunity.comments'=>'required|filled', 'opportunity.closed'=>'required|in:1,2']);
        
        $this->doClose('closeOpportunityModal');
        
        $this->opportunity->update(['actual_close' => Carbon::parse($this->opportunity->actual_close)]);

    }
    /**
     * [doShow description]
     * 
     * @param [type] $form [description]
     * 
     * @return [type]       [description]
     */
    public function doShow($form=null)
    {
      
        $this->$form =true;
        
    }
    /**
     * [doClose description]
     * 
     * @param [type] $form [description]
     * 
     * @return [type]       [description]
     */
    public function doClose($form=null)
    {
        $this->$form= false;
               
        
    }

    /**
     * [changeOpportunityType description]
     * 
     * @param Opportunity $opportunity [description]
     * @param [type]      $type        [description]
     * 
     * @return [type]                   [description]
     */
    public function changeOpportunityType(Opportunity $opportunity, $type)
    {

        ! $opportunity->$type ?  $opportunity->$type=1 : $opportunity->$type=null;

        $opportunity->save();

    }
}
