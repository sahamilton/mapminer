<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Address;
use App\Contact;
use App\Activity;
use App\Opportunity;
use App\ActivityType;

class AddressCard extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';

    public $activitytype = "All";

    public $paginationTheme = 'bootstrap';
    public $view = 'summary';
    public $owned = false;
    public $status = 0;
    public $branch_id;
    
    public array $myBranches;
  

    // activities
    public $address_id;
    public $activitytype_id;
    public $activity_date;
    public $note;
    public $completed = true;
    public $followup_date;
    public $followup_activity;
    public $activityModalShow = false;


    // opportunities

    public $closed;
    public $Top25;
    public $value;
    public $requirements;
    public $duration;
    public $description;
    public $comments;
    public $title;
    public $expected_close;
    public $actual_close;
    public $csp = 0;
    public $address_branch_id;

    public $opportunityModalShow = false;



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
       
        $this->address_id = $address_id;
        $this->myBranches = $this->_getMyBranches();
        $this->owned = $this->_checkIfOwned();
        $this->activityTypes = ActivityType::pluck('activity', 'id')->toArray();
        isset($view) ? $this->view = $view : $this->view = 'summary';
        $address = Address::with('claimedByBranch')->findOrFail($address_id)->claimedByBranch->first();
        $this->branch_id = $address->id; 
        $this->address_branch_id = $address->pivot->id;
      
    }
    public function render()
    {
        return view(
            'livewire.address-card',
            [

                'address'=>Address::withCount('activities', 'contacts', 'opportunities')->findOrFail($this->address_id),
                'viewdata'=> $this->_getViewData(),
                'statuses' =>['all'=>'All', 0=>'Open',1=>'Closed-Won',2=>'Closed-Lost'],
                'activityTypes' => $this->_getActivityTypes(),
                'viewtypes'=>[
                    'summary'=>'Summary',
                    'contacts'=>'Contacts', 
                    'activities'=>'Activities', 
                    'opportunities'=>'Opportunities'],

            ]
        );
    }
    private function _getViewData()
    {
        switch($this->view) {
            case 'summary':
                

                break;
            
            case 'contacts':
                return Contact::where('address_id', $this->address_id)
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);
                break;
            
            case 'activities':

                return Activity::where('address_id', $this->address_id)
                    ->when($this->activitytype != 'All', function ($q) {
                        $q->where('activitytype_id', $this->activitytype);
                    })
                    ->with('relatesToAddress.contacts')
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);
                break;
            
            case 'opportunities':
                return Opportunity::query()
                    ->where('address_id', $this->address_id)
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
                    ->paginate($this->perPage);
                break;
        }
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
        
        $assignedTo = Address::with('assignedToBranch')->findOrFail($this->address_id)
            ->assignedToBranch
            ->where('pivot.status_id', 2)
            ->pluck('id')
            ->toArray();
        return array_intersect($assignedTo, $this->myBranches);

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

    private function _getActivityTypes()
    {
        $activityTypes = ActivityType::orderBy('activity')->pluck('activity', 'id')->toArray();
        $activityTypes['0'] = 'All';
       
        sort($activityTypes);

        return $activityTypes;
    }

    /*

        Adding activities



    */
    public function addActivity(Address $address)
    {
     
        // get contacts;
        $this->resetActivities();
        $this->doShow();
       
        $this->address = $address;
       

    }
    /**
     * [rules description]
     * 
     * @return [type] [description]
     */
    public function rules()
    {
       
        $activityDateRules = 'date|required';
        if ($this->completed) {
            $activityDateRules.='|before:tomorrow';
        }

        return [
            'activity_date'=> $activityDateRules,
            'activitytype_id'=>'required',
            'note'=>'required',
            'followup_date'=>'date|nullable|after:activity_date',
            'followup_activity'=>'required_with:followup_date',
            'address_id' => 'required',
            'branch_id'=>'required',
        ];
    }
    /**
     * [$messages description]
     * 
     * @var [type]
     */
    protected $messages = [
        'activity_date.before' => 'Completed activities cannot be in the future',
        
    ];
    
    /**
     * [resetActivities description]
     * 
     * @return [type] [description]
     */
    private function resetActivities()
    {
        $this->note=null;
        $this->completed = 1;
        $this->activity_date = now()->format('Y-m-d');
        $this->followup_date=null;
        $this->activitytype_id = 13;
        $this->followup_activity = 13;
        $this->contact_id = null;

 
    }
    /**
     * [store description]
     * 
     * @return [type] [description]
     */
    public function storeActivity()
    {
        $this->_getActivity();
        $new = $this->_recordActivity();
        $message =  ($this->completed ? 'Completed ' : 'To do ') . $this->activityTypes[$this->activitytype_id] .' activity added at '. $this->address->businessname;
        session()->flash('message', $message);
        if ($this->followup_date) {
            $followup = $this->_recordFollowUpactivity();
            $new->update(['relatedActivity'=>$followup->id]);
        }
        $this->resetActivities();
        $this->activityModalShow = false;
    }
   
    /**
     * [_recordFollowUpactivity description]
     * 
     * @return [type] [description]
     */
    private function _recordFollowUpactivity() {

        $activity = [
            'address_id' => $this->address_id,
            'activity_date' => $this->followup_date,
            'activitytype_id'=> $this->followup_activity,
            'branch_id' => $this->branch_id,
            'completed' => null,
            'note'=> "Follow up to prior " . $this->activityTypes[$this->activitytype_id] . " on " . Carbon::parse($this->activity_date)->format('m/d/y') . " (". $this->note . ")",
            'user_id' => auth()->user()->id,
        ];
       
        
        return Activity::create($activity);
        
    }
    /**
     * [_recordActivity description]
     * 
     * @return [type] [description]
     */
    private function _recordActivity()
    {
         $activity = [
            'address_id' => $this->address_id,
            'activitytype_id'=> $this->activitytype_id,
            'branch_id' => $this->branch_id,
            'completed' => $this->completed,
            'note'=>$this->note,
            'user_id' => auth()->user()->id,
            'activity_date' => $this->activity_date,
            'followup_date'=> $this->followup_date,
        ];
        
        
         $activity = Activity::create($activity);
      
         if($this->contact_id && $this->contact_id !==0) {
            $activity->relatedContact()->attach($this->contact_id);
         }
        return $activity;
    }
    /**
     * [_getActivity description]
     * 
     * @return [type] [description]
     */
    private function _getActivity() 
    {
            
            
            $this->validate();
            
    }


    /*

        Adding opportunities



    */
    public function addOpportunity(Address $address)
    {
     
        // get contacts;
        $this->resetOpportunities($address);
        $this->doShow();
       
        $this->address = $address;
       

    }
    
    /**
     * [resetActivities description]
     * 
     * @return [type] [description]
     */
    private function resetOpportunities(Address $address = null)
    {
        
        $this->title="Opportunity @ " . isset($address) ? $address->businessname : '';
        
        $this->requirements;
        $this->duration;
        $this->value;
        $this->closed = null;
        $this->description;
        $this->comments;
        $this->csp = null;
        $this->Top25 = null;
        $this->expected_close = null;
        $this->actual_close = null;

 
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
        $this->doClose();
    }
   
    
    /**
     * [_recordActivity description]
     * 
     * @return [type] [description]
     */
    private function _recordOpportunity()
    {
         $opportunity = [
            'address_id' => $this->address_id,
            
            'branch_id' => $this->branch_id,
            
            'address_branch_id'=>$this->address_branch_id,
            'user_id' => auth()->user()->id,
            'title'=>$this->title,
            'requirements'=>$this->requirements,
            'duration'=> $this->duration,
            'value'=>$this->value,
            'expected_close' => $this->expected_close,
            'actual_close'=> $this->actual_close,
            'csp'=>$this->csp,
            'Top25'=>$this->Top25,
        ];
        
        @ray($opportunity);
         $opportunity = Opportunity::create($opportunity);
      
        
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

            'title'=>'required',
            'expected_close'=> 'date',
            'value' => 'numeric|min:1', 
            'requirements' => 'numeric|min:1', 
            'duration' => 'numeric|min:1', 
            'description'=>'required',

        ] ;   
        $this->validate($rules);
            
    }
    public function doShow()
    {
        switch($this->view) {
            case 'activities':
                $this->activityModalShow = true;
                break;
                
            case 'opportunities':
                $this->opportunityModalShow = true;
                break;
        }
        
    }
    public function doClose()
    {
        switch($this->view) {
            case 'activities':
                $this->activityModalShow = false;
                break;

            case 'opportunities':
                $this->opportunityModalShow = false;
                break;
        }
        
    }
    
}
