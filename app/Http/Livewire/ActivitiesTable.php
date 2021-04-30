<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\ActivityType;
use App\Branch;
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

    public $setPeriod = 'thisWeek';

    public $branch_id;
    public $myBranches;
 
    public $status='All';
    public $user;
    public $paginationTheme = 'bootstrap';


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
    public function mount()
    {
        
        $this->myBranches = auth()->user()->person->myBranches();
        $this->branch_id = array_key_first($this->myBranches);


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
                    ->periodActions($this->period)
                    ->with('relatesToAddress', 'type')
                    ->search($this->search)
                    ->when(
                        $this->status != 'All', function ($q) {
                            if ($this->status ==='') {
                                $this->status = null;
                            } 
                            $q->where('completed', $this->status);
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
        if ($this->setPeriod != session('period')) {
            $this->livewirePeriod($this->setPeriod);
            
        }
    }

    private function _setBranchSession()
    {
        session(['branch'=>$this->branch_id]);
    }


}
