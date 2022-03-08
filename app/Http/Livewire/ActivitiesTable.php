<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\ActivityType;
use App\Branch;
use App\Campaign;
use App\Activity;
use App\User;
use Livewire\WithPagination;
use App\PeriodSelector;

class ActivitiesTable extends Component
{
    use WithPagination, PeriodSelector;

    public $perPage = 10;
    public $sortField = 'activity_date';
    public $activitytype='All';
    public $sortAsc = false;
    public $search ='';
    public $campaign_id = 'all';
    public $setPeriod;
    public $campaign = ['datefrom'=>null, 'dateto'=>null];
    public $branch_id;
    public $myBranches;
    public $team;
    public $status='All';
    public $user;
    public $selectuser = 'All';
    public $paginationTheme = 'bootstrap';


    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatedCampaignId()
    {
       
        $this->_getCampaign();
        $this->resetPage();
    }
    public function updatingSetPeriod()
    {
        $this->resetPage();
    }
    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingActivitytype()
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
    public function mount($branch=null, $status = null)
    {
        
        $this->myBranches = auth()->user()->person->myBranches();
        if ($branch) {
            $this->branch_id = $branch;
        } else {
            $this->branch_id = array_key_first($this->myBranches);
        }
        $this->team = auth()->user()->person->myTeam()->get()->pluck('full_name', 'user_id')->toArray();

        if ($status) {
            $this->status = $status;
        }
        
        
        if (! session()->has('period')) {
            $this-> _setPeriod();
        } 
        if ($status == 0) {
            $this->setPeriod = 'allDates';
        } else {
            $this->setPeriod = session('period')['period']; 
        }
        //$this->setPeriod = session('period')['period'];
        

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
            'livewire.activities-table',
            [
                
                
                
                'activities'=>Activity::query()
                    ->where('branch_id', $this->branch_id)
                    ->periodActivities($this->period)
                    ->when(
                        $this->selectuser != 'All', function ($q) {
                            $q->where('user_id', $this->selectuser);
                        }
                    )
                    ->with('relatesToAddress', 'type')
                    ->search($this->search)
                    ->when(
                        $this->status != 'All', function ($q) {
                            if ($this->status ==='1') {
                                $q->whereNotNull('completed');
                            } else {
                                $q->whereNull('completed');
                            } 
                            
                        }
                    )
                    ->when(
                        $this->campaign_id != 'all', function ($q) {
                            $q->whereBetween('activity_date', [$this->campaign['datefrom'], $this->campaign['dateto']])
                                ->whereHas(
                                    'relatesToAddress', function ($q) {
                                        $q->whereHas(
                                            'campaigns', function ($q) {
                                                $q->where('campaigns.id', $this->campaign_id);
                                            }
                                        );
                                    }
                                );
                        }
                    )
                    ->when(
                        $this->activitytype != 'All', function ($q) {

                            $q->where('activitytype_id', $this->activitytype);
                        }
                    )
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'activitytypes' => ActivityType::orderBy('activity')->pluck('activity', 'id')->toArray(),
                'branch' => Branch::findOrFail($this->branch_id),
                'statuses' => ['All'=>'All', '1'=>'Completed', '0'=>'Planned'],
                'campaigns'=> Campaign::active()
                    ->current([$this->branch_id])
                    ->pluck('title', 'id')
                    ->toArray(),
                           ]
        );
    }
    /**
     * [_setPeriod description]
     *
     * @return setPeriod
     */
    private function _setPeriod()
    {
        
        $this->livewirePeriod($this->setPeriod);
            
        
    }

    private function _setBranchSession()
    {
        session(['branch'=>$this->branch_id]);
    }

    private function _getCampaign()
    {
        
        if ($this->campaign_id == 'all') {
            $this->campaign = ['datefrom'=>null, 'dateto'=>null];
        } else {
            $campaign = Campaign::findOrFail($this->campaign_id);
        
            $this->campaign =  ['datefrom'=>$campaign->datefrom, 'dateto'=>$campaign->dateto];
        }
        
        
    }


}
