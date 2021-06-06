<?php

namespace App\Http\Livewire;
use App\Person;
use App\Role;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'lastname';
    public $sortAsc = true;
    public $search ='';
    public $showRoles = [3,6,7,9,14];
    public $defaultRoles = [3,6,7,9,14];
    public $servicelines; 

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
            $this->servicelines = auth()->user()->currentServiceLineIds();
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        return view(
            'livewire.dashboard-table',
            [
            'managers' => Person::query()->wherehas(
                'userdetails.roles', function ($q) {
                    $q->whereIn('role_id', $this->showRoles);
                }
            )
            ->with('userdetails.roles', 'reportsTo', 'branchesServiced')
            ->whereHas(
                'userdetails.serviceline', function ($q) {
                    $q->whereIn('serviceline_id', array_keys($this->servicelines));
                }
            )
            ->when(
                $this->search && $this->search !='All', function ($q) {
                    $q->search($this->search);
                }
            )
            ->distinct()
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage),
            'roles'=>Role::whereIn('id', $this->defaultRoles)->select('id', 'display_name')->get(),

            ]
        );

    }
}
