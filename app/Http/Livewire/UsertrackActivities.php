<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\ActivityType;
use App\Branch;
use App\Activity;
use Livewire\WithPagination;

class UsertrackActivities extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'activity_date';
    public $activitytype='All';
    public $sortAsc = false;
    public $search ='';
    public $branch;
    public $period;
    public $setPeriod='All';
    public $status='All';
    public $user;



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
    public function mount($user, $period)
    {
        $this->user = $user;
        $this->period = $period;

    }
    public function render()
    {
        
        return view(
            'livewire.usertrack-activities',
            [
                'activities'=>Activity::userActions($this->user)
                ->periodActions($this->period)
                ->with('relatesToAddress', 'type')
                
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'activitytypes' => ActivityType::select('id', 'activity')->orderBy('activity')->get(),
                

            ]
        );
    }

    private function _setPeriod()
    {
        if ($this->setPeriod != 'All') {

            $this->period = $this->activity->getPeriod($this->setPeriod);
        
        } else {
            $this->period = null;
        }


    }
}
