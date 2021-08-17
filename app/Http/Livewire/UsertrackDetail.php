<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Person;
use Livewire\PeriodSelector;
class UsertrackDetail extends Component
{
    use WithPagination, PeriodSelector;
    
    public $manager;
   
    public $perPage = 10;
    public $sortField = 'logins_count';
    public $sortAsc = false;
    public $search = '';
    public $paginationTheme = 'bootstrap';
    public $setPeriod;


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
   
    public function mount($manager)
    {
        $this->manager = $manager;
        
    }

    public function render()
    {
        return view(
            'livewire.users.usertrack-detail',
            [
                'activities'=>Activity::query()
                    ->where('user_id', $this->manager->user_id)
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
                'managers'=>$this->manager->managers(),


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
}
