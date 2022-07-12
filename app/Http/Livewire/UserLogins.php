<?php

namespace App\Http\Livewire;
use App\User;
use App\Role;

use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;

class UserLogins extends Component
{
    use WithPagination, PeriodSelector;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'email';
    public $sortAsc = true;
    public $search ='';

    public $roletype='all';
    public $setPeriod = 'All';

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



    public function render()
    {
        
        $this->_setPeriod();
        return view('livewire.user-logins',
            [
                'users'=>User::query()
                    ->when(
                       $this->setPeriod !='All', function ($q) {
                            $q->withCount(
                                [
                                    'usage'=>function ($q) {
                                        $q->whereBetween('lastactivity',[$this->period['from'], $this->period['to']]);
                                    }
                                ]
                            );
                           
                        }
                    )
                    ->when(
                        $this->roletype != 'all', function ($q) {
                            $q->whereHas(
                                'roles', function ($q) {
                                    $q->whereIn('roles.id', [$this->roletype]);
                                }
                            );
                        }
                    )
                    ->when(
                       $this->setPeriod !='All', function ($q) {
                            $q->whereBetween('lastlogin', [$this->period['from'], $this->period['to']]);
                        })
                    ->with('roles', 'person')
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),

                'roles'=>Role::pluck('display_name', 'id')->prepend('All', 'all'),

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
}
