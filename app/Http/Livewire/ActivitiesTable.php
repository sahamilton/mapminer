<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Person;
use App\ActivityType;
use App\Branch;
use App\Activity;
class ActivitiesTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'activity_date';
    public $activitytype='All';
    public $sortAsc = false;
    public $search ='';
    public $branch_id;
    public $period;
    public $setPeriod='lastWeek';
    public $status='All';
    public $filter = 0;
    public $myBranches;

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
        
        $person = new Person();
        $this->myBranches = $person->myBranches();
        $this->branch_id = array_key_first($this->myBranches);

    }
    public function render()
    {
        

        $this->_setPeriod(); 

        
        return view(
            'livewire.activities-table', [
                'activities'=>Activity::query()
                    ->where('branch_id', $this->branch_id)
                    ->select('activities.*', 'addresses.id', 'addresses.businessname')
                    ->join('addresses', 'addresses.id', '=', 'address_id')
                    ->with('type')
                    ->when(
                        $this->status != 'All', function ($q) {
                            if ($this->status ==='') {
                                $this->status = null;
                            } 
                            $q->where('completed', $this->status);
                        }
                    )
                    ->when(
                        $this->period, function ($q) {
                            
                            $q->whereBetween('activity_date', [$this->period['from'], $this->period['to']]);
                        }
                    )
                    ->when(
                        $this->activitytype != 'All', function ($q) {

                            $q->where('activitytype_id', $this->activitytype);
                        }
                    )
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'activitytypes' => ActivityType::select('id', 'activity')->orderBy('activity')->get(),
                'branch'=> Branch::findOrFail($this->branch_id),


            ]
        );
    }
    
    private function _setPeriod()
    {
        if ($this->setPeriod != 'All') {
            $model = new Branch();
            $this->period = $model->getPeriod($this->setPeriod);
        
        } else {
            $this->period = null;
        }


    }
}
