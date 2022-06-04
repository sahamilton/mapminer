<?php

namespace App\Http\Livewire;
use App\Address;
use App\Activity;
use App\Branch;
use App\Campaign;
use App\Person;
use App\ActivityType;
use Livewire\Component;
use Livewire\WithPagination;
use App\AddressBranch;
use App\PeriodSelector;
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
    public Address $address;
    public $address_id;
    public $activitytype_id;
    public $activity_date;
    public $note;
    public $completed;
    public $followup_date;
    public $followup_activity;
    public $show = false;

    public $contact_id=null;

 
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingLeadSourceId()
    {
        $this->resetPage();
    }
    public function updatingBranchId()
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
     * @return [type]         [description]
     */
    public function mount($branch, $search = null)
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
                                $q->where('isCustomer', 1);
                            
                            }
                        )
                        ->when(
                            $this->type == 'Lead', function ($q) {
                                $q->where('isCustomer', 1);
                            
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
            
                ->with('assignedToBranch')
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
        // get contacts;
        $this->resetActivities();
        $this->doShow();
       
        $this->address = $address;
        $this->address_id = $this->address->id;
       
       
        

    }
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

    protected $messages = [
        'activity_date.before' => 'Completed activities cannot be in the future',
        
    ];
    public function doShow() {
        $this->show = true;
    }

    public function doClose() {
        
        $this->show = false;
    }

    private function resetActivities()
    {
        $this->note=null;
        $this->completed = null;
        $this->activity_date = now()->format('Y-m-d');
        $this->followup_date=null;
        $this->activitytype_id = 13;
        $this->followup_activity = 13;
        $this->contact_id = null;
 
    }

    public function store()
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
        $this->show = false;
    }
   
    
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
    private function _getActivity() 
    {
            
            
            $this->validate();
            
    }
    
    
}
