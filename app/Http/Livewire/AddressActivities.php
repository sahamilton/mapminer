<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Activity;
use App\ActivityType;
use App\Address;
use Livewire\WithPagination;
class AddressActivities extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';
    public array $owned;
    public $address_id;



    // activities
   
    public $activitytype_id='all';
    public $activity_date;
    public $note;
    public $completed = true;
    public $followup_date;
    public $followup_activity;
    public $activityModalShow = false;

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
            'activityTypes' => ActivityType::pluck('activity', 'id')->toArray(),

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
        $this->doClose('activityModalShow');
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

    public function doClose($form)
    {
        $this->$form = false;
    }
    public function doShow($form)
    {
        $this->$form = true;
    }

}
