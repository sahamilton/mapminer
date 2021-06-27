<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Person;
use App\Role;


class ManagerTable extends Component
{
    
    public Person $capoDiCapo;
    public array $role_ids = ['All'];
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
        return view(
            'livewire.manager-table', 
            ['managers'=>$this->capoDiCapo
                ->descendantsandSelf()
                ->search($this->search)
                ->when(
                    ! in_array('All', $this->role_ids), function ($q) {
                        $q->whereIn('userdetails.roles', $this->role_ids);
                    }
                )
                ->withCount('branchesserviced')
                ->withCount('directReports')
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'roles'=> Role::orderBy('display_name')->pluck('display_name', 'id'),
            ]
        );
    }
}
