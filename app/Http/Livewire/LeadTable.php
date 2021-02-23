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
     * @return [type]         [description]
     */
    public function mount($branch, $search = null)
    {
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
        //$branches = auth()->user()->person->myBranches();
        $this->_setBranchSession();
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

    private function _setBranchSession()
    {
        session(['branch'=>$this->branch_id]);
    }
}
