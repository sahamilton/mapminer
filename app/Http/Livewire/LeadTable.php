<?php

namespace App\Http\Livewire;
use App\Models\Address;
use App\Models\Activity;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Person;
use App\Models\ActivityType;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AddressBranch;
use App\Models\PeriodSelector;
use Carbon\Carbon;

class LeadTable extends Component
{
    use WithPagination, PeriodSelector;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = null;
   
    public $withOps = 'All';
    //public $updateMode = false;
    public $setPeriod = 'All';
    public $selectuser = 'All';
    public $team;
    public $type= 'Either';
    
    
    public $branch_id;
    public $lead_source_id = 'All';
    public $campaign_id = 'All';
    public $myBranches;
    
    //activities

    public $addActivityModal = true;
    public array $activityTypes;
    public $address;

    public $activity;
    public $address_id;
    public $activitytype_id;
    public $activity_date;
    public $note;
    public $completed;
    public $followup_date;
    public $followup_activity;
    public $activityModalShow = false;
    public $address_branch_id;
    
    public $contact_id = null;
    public $followupactivitydate;
    public $followupactivitytype;
    public $user_id;
    public $title = 'Record';
    public $method ='storeActivity';
   

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
     * [updatingLeadSourceId description]
     * 
     * @return [type] [description]
     */
    public function updatingLeadSourceId() :void
    {
        $this->resetPage();
    }
    /**
     * [updatingBranchId description]
     * 
     * @return [type] [description]
     */
    public function updatingBranchId() :void
    {
        $this->resetPage();
        $this->lead_source_id = 'All';
        $this->campaign_id = 'All';

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
     * @param int    $branch [description]
     * @param [type] $search [description]
     * 
     * @return [type]         [description]
     */
    public function mount(int $branch, $search = null) :void
    {
        $person = new Person();
        $this->myBranches = $person->myBranches();
        $this->search = $search;
        $this->branch_id = $branch;
        if (! session()->has('period')) {
            $this-> _setPeriod();
        } 
        $this->setPeriod = session('period')['period'];
        $this->team = Branch::with('branchTeam')
            ->findOrFail($this->branch_id)
            ->branchTeam->pluck('full_name', 'user_id')
            ->toArray();
        $this->activityTypes = ActivityType::pluck('activity', 'id')->toArray();
        $this->user_id = auth()->user()->id;    
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {

        $this->_setPeriod();
        $this->_setBranchSession();
        return view(
            'livewire.lead-table', [
            'leads' => Address::query()
                ->search($this->search)
                
                ->when(
                    $this->type != 'Either', function ($q) {
                       
                        $q->when(
                            $this->type == 'Customers', function ($q) {
                                $q->whereNotNull('isCustomer');
                            
                            }, function ($q) {
                                $q->whereNull('isCustomer');
                            
                            }
                        );
                    }
                )
                ->when(
                    $this->selectuser != 'All', function ($q) {
                        $q->where('addresses.user_id', $this->selectuser);
                    }
                )
                ->when(
                    $this->withOps != 'All', function ($q) {
                        $q->when(
                            $this->withOps == 'Without', function ($q) {
                                $q->doesntHave('opportunities');
                            }
                        )
                        ->when(
                            $this->withOps == 'Only Open', function ($q) {
                                $q->whereHas(
                                    'opportunities', function ($q) {
                                        $q->where('closed', 0);
                                    }
                                );
                                
                            }
                        )
                        ->when(
                            $this->withOps == 'Top 25', function ($q) {
                                $q->whereHas(
                                    'opportunities', function ($q) {
                                        $q->where('closed', 0)
                                            ->where('Top25', 1);
                                    }
                                );
                                
                            }
                        )
                        ->when(
                            $this->withOps == 'Any', function ($q) {
                                $q->has('opportunities');
                            }
                        );
                        
                    }
                )
                ->when(
                    $this->campaign_id != 'All', function ($q) {
                        $q->whereHas(
                            'campaigns', function ($q) {
                                $q->where('campaigns.id', $this->campaign_id);
                            }
                        );
                    }
                )->whereIn(
                    'addresses.id', function ($query) {
                        $query->select('address_id')
                            ->from('address_branch')
                            ->where('branch_id', $this->branch_id)
                            ->where('status_id', 2)
                            ->when(
                                $this->setPeriod != 'All', function ($q) {
                                    $q->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                                }
                            );
                    }
                )
                ->whereHas(
                    'claimedByBranch', function ($q) {
                        $q->whereIn('branch_id', [$this->branch_id])
                            ->when(
                                $this->setPeriod != 'All', function ($q) {
                                    $q->whereBetween('address_branch.created_at', [$this->period['from'], $this->period['to']]);
                                }
                            );
                    }
                )->with('claimedByBranch')
                ->when(
                    $this->lead_source_id != 'All', function ($q) {
                        $q->where('lead_source_id', $this->lead_source_id);
                    }
                )
                
                ->withLastActivityId()
                ->with('lastActivity')
                ->dateAdded()
                ->orderByColumn($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'branch'=>Branch::query()
                    ->with('manager', 'currentcampaigns', 'currentopencampaigns')->findOrFail($this->branch_id),
                'types'=>['Either', 'Leads', 'Customers'],
                'opstatus'=>['All', 'Without', 'Only Open', 'Top 25', 'Any'],
                
                'leadsources' => $this->_getLeadSources(),
                'campaigns'=> Campaign::active()
                    ->current([$this->branch_id])
                    ->pluck('title', 'id')
                    ->toArray(),
            ]
        );
    }

    private function _setBranchSession()
    {
        session(['branch'=>$this->branch_id]);
    }

    private function _setPeriod()
    {
        $this->livewirePeriod($this->setPeriod);
        
    }

    private function _getLeadSources()
    {
        return collect(
            \DB::select(
                \DB::raw(
                    "select distinct leadsources.id, 
                        leadsources.source 
                        from address_branch, addresses, leadsources 
                        where address_branch.branch_id = ". $this->branch_id .
                        " and addresses.id = address_branch.address_id
                        and address_branch.status_id = 2 
                        and addresses.lead_source_id = leadsources.id"
                )
            )
        )
            ->sortBy('source')
            ->pluck('source', 'id')
            ->prepend('All', 'All')
            ->toarray();
        
    }

    public function changeCustomer(Address $address)
    {
        if ($address->isCustomer) {
            $address->update(['isCustomer'=>null]);
        } else {
            $address->update(['isCustomer'=>1]);
        }
    }
    public function changeTop50(AddressBranch $address)
    {
        if ($address->top50) {
            $address->update(['top50'=>null]);
        } else {
            $address->update(['top50'=>1]);
        }
    }
    /*

        Adding activities



    */
    public function addActivity(Address $address)
    {
       
        $this->address = $address;
        $this->address_id = $this->address->id;
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
            'contact_id' => 'sometimes',
            
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
     * [doShow description]
     * @return [type] [description]
     */
    public function doShow()
    {
        $this->resetErrorBag();
        $this->activityModalShow = true;

    }

    public function doClose() 
    {
        
        $this->activityModalShow = false;
        $this->resetErrorBag();
    }

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
        $this->contact_id = null;

       
    }

    /**
     * [store description]
     * 
     * @return [type] [description]
     */
    public function storeActivity()
    {
        
        $this->validate();
        if ($this->activity->followup_date < $this->activity->activity_date) {
            $this->activity->followup_date = null;
        } 
        $this->doClose('activityModalShow');
       
        $this->activity->save();
        if ($this->contact_id && $this->contact_id !== 0) {
            $this->activity->relatedContact()->attach($this->contact_id);
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
    
    
    
}
