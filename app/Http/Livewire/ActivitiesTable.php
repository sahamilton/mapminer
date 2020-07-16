<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

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
    public $branch;
    public $period;
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
    public function mount($branch)
    {
        $this->branch = Branch::findOrFail($branch->id);
        $this->period = session('period');
    }
    public function render()
    {

        return view('livewire.activities-table', [
               'activities'=>Activity::query()
                ->where('branch_id', $this->branch->id)
                ->select('activities.*', 'addresses.id', 'addresses.businessname')
                ->join('addresses', 'addresses.id', '=', 'address_id')
                ->with('type')
                ->when($this->status != 'All', function ($q)
                     {
                        if($this->status ==='') {
                            $this->status = null;
                        } 
                        $q->where('completed', $this->status);
                     }
                 )
                ->when($this->activitytype != 'All', function ($q) {

                    $q->where('activitytype_id', $this->activitytype);
                })
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'activitytypes' => ActivityType::select('id', 'activity')->orderBy('activity')->get(),



        ]);
        /*
        $data['activities'] = ->get();
         */
    }
}
