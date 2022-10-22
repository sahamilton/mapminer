<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Contact;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Branch;

use Livewire\WithPagination;
use App\Models\PeriodSelector;
use Carbon\Carbon;

class ContactDetail extends Component
{
    use WithPagination, PeriodSelector;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public Branch $branch;
    public $search = '';
    public $branch_id;
    public array $myBranches;
    public $setPeriod='all';
    public $status='all';
    public Contact $contact;
    public $contact_id;
    public array $mybranches;

    public $owned;

    public $activity;
    public $view;
    public $activityModalShow = false;
    public $activitytype_id = 'all';
    public $address_branch_id;

    public $followupactivitydate;
    public $followupactivitytype;
    public $user_id;
    public array $activityTypes;
    public $title = 'Record';
    public $method ='storeActivity';

    public $confirmModal = false;
   
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
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch() :void
    {
        $this->resetPage();
    }
    /**
     * [mount description]
     * 
     * @param  Contact $contact [description]
     * @return [type]           [description]
     */
    public function mount(Contact $contact)
    {
        $this->contact = $contact->load('location', 'user');
        $this->address = $this->contact->location;
        $this->contact_id = $contact->id;
        $this->myBranches = auth()->user()->person->getMyBranches();
        if ($branch = $this->address->claimedByBranch->first()) {
            $this->branch = $branch;

            $this->branch_id = $branch->id; 
            $this->address_branch_id = $branch->pivot->id;
            if (in_array($this->branch_id, $this->myBranches)) {
                $this->owned = true;
            }
            
        }

        $this->user_id = auth()->user()->id;
        
        $this->_getActivityTypes();

    }
   

    public function render()
    {
        $this->_setPeriod(); 
        return view(
            'livewire.contact-detail',
            [
                'activities'=>$this->_getActivities(),
                'activitytypes'=>ActivityType::pluck('activity', 'id')->prepend('All', 'all')->toArray(),

            ]
        );
    }
    private function _getActivities()
    {

        return Activity::whereHas(
            'relatedContact', function ($q) {
                $q->where('contacts.id', $this->contact_id);

            }
        )
        ->when(
            $this->activitytype_id != 'all', function ($q) {
                $q->where('activitytype_id', $this->activitytype_id);
            }
        )
        ->with('user.person')
        ->when(
            $this->setPeriod != 'all', function ($q) {
                $q->periodActivities($this->period);
        
            }
        )
        ->whereIn('branch_id', $this->myBranches)
        ->search($this->search)
        ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
        ->paginate($this->perPage);

    }
    /**
     * [_getActivityTypes description]
     * 
     * @return [type] [description]
     */
    private function _getActivityTypes()
    {
        $this->activityTypes = ActivityType::query()
            ->orderBy('activity')
            ->pluck('activity', 'id')
            ->toArray();
    }
    /*

        Adding activities



    */
    public function addActivity()
    {
       
        
        $this->_resetActivities('create');
        $this->title='Record';
        $this->method='storeActivity';
        $this->doShow('activityModalShow');
       

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
            'activity.activitytype_id'=>'required|regex:/^\d*(\.\d{2})?$/',
            'activity.note'=>'required',
           
            'activity.address_id' => 'required',
            'activity.branch_id' => 'required',
            'activity.user_id' => 'required',
            'activity.completed'=>'sometimes',
   
            'activity.followup_date'=>'sometimes|date|nullable|after_or_equal:activity.activity_date|after:yesterday',
            'followupactivitytype' =>'sometimes|regex:/^\d*(\.\d{2})?$/',
            'followupactivitytype' => 'required_with:activity.followup_date',
            'contact_id' => 'required',
            
        ];
    }
    /**
     * [$messages description]
     * 
     * @var [type]
     */
    protected $messages = [
        'activity.activity_date.before' => 'Completed activities cannot be in the future',
        'activity.followup_date.after_or_equal' =>'Follow up date cannot be before original activity date',
        'activity.activitytype_id.regex' => 'Select a valid activity type',
        'activity.activitytype_id:' =>'Select an activity type',
        'followupactivitytype.numeric' => 'Select a valid activity type',
        'followupactivitytype.required_with' => 'Select a valid follow up activity type',
    ];
    
    /**
     * [_resetActivities description]
     * 
     * @param Address $address [description]
     * 
     * @return [type]           [description]
     */
    private function _resetActivities($type='create')
    {
        if ($type == 'create') {
            $this->activity = Activity::make(
                [
                    'branch_id'=>$this->branch_id,
                    'activity_date' => now(), 
                    'address_id'=> $this->address->id,
                    'completed' => null,
                    'user_id' => $this->user_id,
                    'address_branch_id'=>$this->address_branch_id,
                    
                    'followup_date'=>null,
                ]
            );
        }
       
        $this->followupactivitytype = null;
        

       
    }
    /**
     * [storeActivity description]
     * 
     * @return [type] [description]
     */
    public function storeActivity()
    {
       
        $this->validate(); 
        $this->doClose('activityModalShow');
        if ($this->activity->followup_date < $this->activity->activity_date) {
            $this->activity->followup_date = null;
        }
        $this->activity->save();
        if ($this->contact_id && $this->contact_id !== 0) {
            $this->activity->relatedContact()->sync($this->contact_id);
        }
        $message =  ($this->activity->completed ? 'Completed ' : 'To do ') . ' activity added at '. $this->address->businessname;
        session()->flash('message', $message);
        if ($this->activity->followup_date) {
            $followup = $this->_recordFollowUpactivity();
            
            $this->activity->update(['relatedActivity'=>$followup->id]);
           
        }
        $this->_resetActivities();
       
    }
   
    /**
     * [_recordFollowUpactivity description]
     * 
     * @return [type] [description]
     */
    private function _recordFollowUpactivity()
    {
        if ($this->activity->relatedActivity) {

            $activity = Activity::findOrFail($this->activity->relatedActivity);
            $followup = ['activity_date'=>$this->activity->followup_date, 'activitytype_id'=>$this->followupactivitytype];
            $activity->update($followup);
            $activity->relatedContact()->sync($this->contact_id);
            return $activity;

        } else {



            
            $activity = [
                'address_id' => $this->activity->address_id,
                'activity_date' => $this->activity->followup_date,
                'activitytype_id'=> $this->followupactivitytype,
                'branch_id' => $this->branch_id,
                'completed' => null,
                'user_id' =>auth()->user()->id,
                'note'=> "Follow up to prior  on " . Carbon::parse($this->activity->activity_date)->format('m/d/y') . " (". $this->activity->note . ")",
                'user_id' => auth()->user()->id,
            ];

        
            return Activity::create($activity);
        }
    }
    
    /**
     * [editActivity description]
     * 
     * @param Activity $activity [description]
     * 
     * @return [type]             [description]
     */
    public function editActivity(Activity $activity)
    {
        $this->title='Edit';
        $this->method = 'updateActivity';
        $this->_resetActivities('edit');
        $this->activity = $activity->load('relatesToAddress.contacts', 'followupActivity');
        if ($this->activity->followupActivity) {
            $this->followupactivitytype = $activity->followupActivity->activitytype_id;
        }
        $this->doShow('activityModalShow');
    }

    /**
     * [updateActivity description]
     * 
     * @param Activity $activity [description]
     * 
     * @return [type]             [description]
     */
    public function updateActivity()
    {

        $this->validate();     
        $this->activity->update();
        if ($this->activity->followup_date) {
            $followup = $this->_recordFollowUpactivity();
            
            $this->activity->update(['relatedActivity'=>$followup->id]);
           
        }
        if ($this->contact_id ) {
            $this->activity->relatedContact()->sync($this->contact_id);
        }
        $this->doClose('activityModalShow');
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
        $this->resetErrorBag();
        $this->resetPage();
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
        $this->resetErrorBag();
    }

    public function deleteActivity(Activity $activity)
    {
        $this->activity = $activity->load('relatesToAddress');
        $this->doShow('confirmModal');
    }
    /**
     * [delete description]
     * 
     * @param Activity $activity [description]
     * 
     * @return [type]             [description]
     */
    public function confirmDelete(Activity $activity)
    {

        $activity->delete();
        $this->_resetActivities();
        $this->doClose('confirmModal');
    }
    /**
     * [_setPeriod description]
     * 
     */
    private function _setPeriod()
    {
        $this->livewirePeriod($this->setPeriod);
    }

}
