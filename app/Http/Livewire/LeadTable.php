<?php

namespace App\Http\Livewire;
use App\Address;
use App\Activity;
use App\Branch;
use App\Person;
use App\ActivityType;
use Livewire\Component;
use Livewire\WithPagination;

class LeadTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = null;
   
    public $withOps = 'All';
    //public $updateMode = false;

    public $activitytype_id;
    public $note;
    public $activity_date='2021-02-03';
    public $completed =1;
    public $followup_date;
    public $followup_activity;
    public $address_id;
    public $branch_id;
    public $lead_source_id = 'All';

    public $myBranches;

 
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingLeadSourceId()
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
     * @param [type] $branch [description]
     * @param [type] $search [description]
     * 
     * @return [type]         [description]
     */
    public function mount($branch, $search = null)
    {
     
        $this->branch_id = $branch;
        $person = new Person();
        $this->myBranches = $person->myBranches();
        $this->search = $search;
        
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        
        $this->_getLeadSources();
        return view(
            'livewire.lead-table', [
            'leads' => AddressBranch::query()
                ->search($this->search)
                
                ->whereIn(
                    'addresses.id', function ($query) {
                        $query->select('address_id')
                            ->from('address_branch')
                            ->where('branch_id', $this->branch_id)
                            ->where('status_id', 2);
                    }
                )
                ->search($this->search)
                ->with('assignedToBranch')
                ->when(
                    $this->withOps != 'All', function ($q) {
                        $q->when(
                            $this->withOps == 'Without', function ($q) {
                                $q->whereDoesntHave('opportunities');
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
                            $this->withOps == 'Any', function ($q) {
                                $q->has('opportunities');
                            }
                        );
                        
                    }
                )
                
                ->withLastActivityId()
                ->with('lastActivity')
                ->dateAdded()
                

                ->orderByColumn($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'branch'=>Branch::query()->with('currentcampaigns')->findOrFail($this->branch_id),
                'opstatus'=>['All', 'Without', 'Only Open', 'Any'],
                'activities'=>ActivityType::pluck('activity', 'id')->toArray(),
            ]
        );
    }

     
    
    /**
     * [_resetInputFields description]
     *
     * @return [type] [description]
     */
    private function _resetInputFields()
    {
        $this->activitytype_id='';
        $this->note='';
        $this->activity_date=now()->format('Y-m-d');
        $this->completed=1;
        $this->followup_date='';
        $this->followup_activity='';
        $this->address_id='';
    


    }
    /**
     * [openModal description]
     *
     * @return [type] [description]
     */
    public function openModal()
    {

        $this->isOpen = true;

    }
    /**
     * [closeModal description]
     *
     * @return [type] [description]
     */
    public function closeModal()
    {

        $this->isOpen = false;

    }

    public function store()
    {
        dd('hree we are');
        $this->validate(
            [
             
            ]
        );

        $activity = Activity::updateOrCreate(
            ['id' => $this->account_id],
            [
                'activitytype_id' => $this->activitytype_id,
                'note' => $this->note,
                'activity_date' => $this->activity_date,
                'completed'=>$this->completed,
                'followup_date'=>$this->followup_date,
                'followup_activity'=>$this->followup_activity,
                'address_id'=>$this->address_id,
                'branch_id'=>$this->branch_id,
                'user_id' => auth()->user()->id

            ]
        );
        ray($activity);
    }
    public function cancel()
    {
        $this->updateMode = false;
        $this->_resetInputFields();
    }


}
