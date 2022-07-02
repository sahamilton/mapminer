<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Activity;
use App\ActivityType;
use App\Address;
use Livewire\WithPagination;
use Carbon\Carbon;

class AddressActivities extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'activity_date';
    public $sortAsc = false;
    public $search ='';
    public array $owned;
    public $address_id;
    public $activitytype_id='all';

    public $followup_date;
    // activities
    public Activity $activity;
    
    public $branch_id;
    public $activityModalShow = false;
    public $address_branch_id;
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


    public function mount(Address $address, array $owned=null){
        $this->address = $address;
        $this->owned = $owned;
        if($branch = Address::with('claimedByBranch')->findOrFail($address->id)->claimedByBranch->first()) {
            $this->branch_id = $branch->id; 
            $this->address_branch_id = $branch->pivot->id;
            
        }
      
    }

    public function render()
    {
        return view('livewire.address-activities', [
            'activities' => Activity::where('address_id', $this->address->id)
                ->when(
                    $this->activitytype_id !='all', function ($q) {
                        $q->where('activitytype_id', $this->activitytype_id);
                    }
                )
                ->with('relatesToAddress.contacts')
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            'activityTypes' =>$this->_getActivityTypes($this->address),

            ]
        );
    }

    /**
     * [_getActivityTypes description]
     * 
     * @return [type] [description]
     */
    private function _getActivityTypes()
    {
        return ActivityType::orderBy('activity')->pluck('activity', 'id')->toArray();
       
    }

    /*

        Adding activities



    */
    public function addActivity(Address $address)
    {
     
        // get contacts;
        $this->resetActivities($address);
        $this->doShow('activityModalShow');
       
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
        if (isset($this->activity) && $this->activity->completed) {
            $activityDateRules.='|before:tomorrow';
        }

        return [
            'activity.activity_date'=> $activityDateRules,
            'activity.activitytype_id'=>'required',
            'activity.note'=>'required',
            
            'activity.address_id' => 'required',
            'activity.completed'=>'sometimes',
            'activity.contact_id'=>'sometimes',
            'activity.followup_date'=>'sometimes|date|after:activity_date',
            'activity.followup_activity' => 'required_with:activity.followup_date',
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
    private function resetActivities(Address $address)
    {
        $this->activity = Activity::make([
              
                'completed' => 1,
                'activity_date' =>now(),
                'branch_id' => $this->branch_id,
                'user_id' => auth()->user()->id,
                'address_id'=>$address->id,
            ]);

       
    }
    /**
     * [store description]
     * 
     * @return [type] [description]
     */
    public function storeActivity()
    {
        $this->validate();
        $new = $this->_recordActivity();
        if($this->activity->contact_id && $this->activity->contact_id !== 0) {
            $new->relatedContact()->attach($this->activity->contact_id);
        }
        $message =  ($this->activity->completed ? 'Completed ' : 'To do ') . ' activity added at '. $this->address->businessname;
        session()->flash('message', $message);
        if ($this->activity->followup_date) {
            $followup = $this->_recordFollowUpactivity();
            $new->update(['relatedActivity'=>$followup->id]);
        }
        $this->resetActivities($this->address);
        $this->doClose('activityModalShow');
    }
   
    /**
     * [_recordFollowUpactivity description]
     * 
     * @return [type] [description]
     */
    private function _recordFollowUpactivity() {

        $activity = [
            'address_id' => $this->address->id,
            'activity_date' => $this->activity->followup_date,
            'activitytype_id'=> $this->activity->followup_activity,
            'branch_id' => $this->branch_id,
            'completed' => null,
            'note'=> "Follow up to prior  on " . Carbon::parse($this->activity->activity_date)->format('m/d/y') . " (". $this->activity->note . ")",
            'user_id' => auth()->user()->id,
        ];
        @ray($activity);
        
        return Activity::create($activity);
        
    }
    /**
     * [_recordActivity description]
     * 
     * @return [type] [description]
     */
    private function _recordActivity()
    {
       
        $data = [
                'activity_date'=>$this->activity->activity_date,
                'activitytype_id'=>$this->activity->activitytype_id,
                'address_id'=>$this->address->id,
                'branch_id'=>$this->branch_id,
                'user_id' =>auth()->user()->id,
                'note'=>$this->activity->note,
                'completed'=>$this->activity->completed,
                'address_branch_id'=>$this->address_branch_id,
            ];
        @ray(211, $data);
        return Activity::create(

            $data

        );
      
    }
    public function updateActivity(Activity $activity)
    {
        @ray($activity);
        $this->activity = $activity;
        $this->doShow('activityModalShow');
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
