<?php

namespace App\Http\Livewire;
use App\Models\Address;
use App\Models\ActivityType;
use App\Models\activity;
use Livewire\Component;

class LeadSearch extends Component
{
    public $lead_id;
    public $branch_id;
    public Activity $activity;
    public $activityModalShow = false;
    public $address_branch_id;
    /**
     * [setLead description]
     * 
     * @param [type] $lead [description]
     */
    public function setLead($lead)
    {
        $this->lead_id = $lead;
    }

    public function mount($branch)
    {
        $this->branch_id = $branch;
    }
    public function render()
    {
        return view(
            'livewire.lead-search',
            [

                'address'=> $this->_getLead(),
                'fields'=>['businessname', 'street', 'city', 'state', 'zip'],
                'activityTypes' =>ActivityType::query()
                    ->orderBy('activity')
                    ->pluck('activity', 'id')
                    ->toArray(),
            ]
        );
    }
    /**
     * [_getLead description]
     * 
     * @return [type] [description]
     */
    private function _getLead()
    {
       
        if ($this->lead_id) {
            $address = Address::with('claimedByBranch', 'contacts')->findOrFail($this->lead_id);
            if ($branch = $address->claimedByBranch->first()) {
                if ($branch->id === $this->branch_id) {
                    $this->address_branch_id = $branch->pivot->id;
                    return $address;
                } else {
                    session()->flash('warning', 'This is not one of your branches leads');
                }
               
            } else {

                session()->flash('warning', 'This is not one of your branches leads');
            }

           
        } else {
            return null;
        }
    }

    public function addActivity(Address $address)
    {
     
        // get contacts;
        $this->_resetActivities($address);
        $this->doShow('activityModalShow');
       
        $this->address = $address;
       

    }
    /**
     * [_resetActivities description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    private function _resetActivities(Address $address)
    {
        $this->activity = Activity::make(
            [
              
                'completed' => 1,
                'activity_date' =>now(),
                'branch_id' => $this->branch_id,
                'user_id' => auth()->user()->id,
                'address_id'=>$address->id,
            ]
        );

       
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
            'activity.followup_date'=>'sometimes|date|nullable|after:activity_date',
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
     * [store description]
     * 
     * @return [type] [description]
     */
    public function storeActivity()
    {
        $this->validate();
        $new = $this->_recordActivity();
        if ($this->activity->contact_id && $this->activity->contact_id !== 0) {
            $new->relatedContact()->attach($this->activity->contact_id);
        }
        $message =  ($this->activity->completed ? 'Completed ' : 'To do ') . ' activity added at '. $this->address->businessname;
        session()->flash('message', $message);
        if ($this->activity->followup_date) {
            $followup = $this->_recordFollowUpactivity();
            $new->update(['relatedActivity'=>$followup->id]);
        }
        $this->_resetActivities($this->address);
        $this->doClose('activityModalShow');
    }
   
    /**
     * [_recordFollowUpactivity description]
     * 
     * @return [type] [description]
     */
    private function _recordFollowUpactivity()
    {

        $activity = [
            'address_id' => $this->address->id,
            'activity_date' => $this->activity->followup_date,
            'activitytype_id'=> $this->activity->followup_activity,
            'branch_id' => $this->branch_id,
            'completed' => null,
            'note'=> "Follow up to prior  on " . Carbon::parse($this->activity->activity_date)->format('m/d/y') . " (". $this->activity->note . ")",
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

        return Activity::create($data);
        
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
    /**
     * [delete description]
     * 
     * @param Activity $activity [description]
     * 
     * @return [type]             [description]
     */
}
