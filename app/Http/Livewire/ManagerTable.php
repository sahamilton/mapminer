<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Person;
use App\Role;
use Livewire\WithPagination;
use App\PeriodSelector;

class ManagerTable extends Component
{
    use WithPagination, PeriodSelector;
    
    public Person $capoDiCapo;
    public $role_id = 'All';
    public $branchcount = 'All';
    public $directReports = 'All';
    public $setPeriod = 'All';
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'lastname';
    public $sortAsc = true;
    public $search = null;


    public function updatingSearch()
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

    public function mount()
    {
        $this->capoDiCapo = Person::findOrFail(config('mapminer.topdog'));
    }


    public function render()
    {
        $this->_setPeriod();
        return view(
            'livewire.manager-table', 
            ['managers'=>Person::query()
                ->with('reportsTo')
                ->select('persons.*')
                ->search($this->search)
                ->join('users', 'persons.user_id', '=', 'users.id')
                ->when(
                    $this->role_id != 'All', function ($q) {
                        $q->whereHas(
                            'userdetails', function ($q) {
                                $q->whereHas(
                                    'roles', function ($q) {
                                        $q->where('roles.id', $this->role_id);
                                    }
                                );
                            } 
                        );
                    }
                )
                ->when(
                    $this->setPeriod != 'All', function ($q) {
                        $q->whereBetween('lastlogin',  $this->period);
                    }
                )
                ->when(
                    $this->branchcount != 'All', function ($q) {
                        $q->when(
                            $this->branchcount == 'no', function ($q) {
                                $q->whereDoesntHave('branchesserviced');
                            }, function ($q) {
                                 $q->whereHas('branchesserviced');
                            }
                        );
                        
                    }
                )->when(
                    $this->directReports != 'All', function ($q) {
                        $q->when(
                            $this->directReports == 'no', function ($q) {
                                $q->whereDoesntHave('directReports');
                            }, function ($q) {
                                 $q->whereHas('directReports');
                            }
                        );
                        
                    }
                )->withCount('branchesserviced')
                ->withCount('directReports')
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'roles'=> Role::orderBy('display_name')->pluck('display_name', 'id'),
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
