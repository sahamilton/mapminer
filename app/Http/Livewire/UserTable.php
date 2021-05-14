<?php

namespace App\Http\Livewire;
use App\User;
use App\Role;
use App\ServiceLine;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search = '';
    public $serviceline ='All';
    public $selectRole = false;
    public $status = 'current';

    

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    

    public function render()
    {
        return view(
            'livewire.user-table', 
            [
                'users' => User::query()
                    ->select('users.*', 'persons.firstname', 'persons.lastname')
                    ->join('persons', 'user_id', '=', 'users.id')
                    ->with('usage',  'serviceline', 'roles')
                    ->when(
                        $this->serviceline != 'All', function ($q) {
                            $q->whereHas(
                                'serviceline', function ($q) {
                                    $q->whereIn('servicelines.id', [$this->serviceline]);
                                }
                            );
                        }
                    )->when(
                        $this->status == 'all', function ($q) {
                            $q->withTrashed()->with('deletedperson');
                        }
                    )->when(
                        $this->status == 'deleted', function ($q) {
                            $q->onlyTrashed()->with('deletedperson');
                        }
                    )
                    ->when(
                        $this->selectRole !='All', function ($q) {
                            $q->whereHas(
                                'roles', function ($q) {
                                    $q->when(
                                        $this->selectRole, function ($q) {
                                            $q->where('roles.id', $this->selectRole);
                                        }
                                    );
                                }
                            );
                        }
                    )
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                    'roles'=>Role::all(),
                    'statuses'=>['all', 'deleted', 'current'],
                    'servicelines'=>ServiceLine::pluck('serviceline', 'id'),
            ]
        );
    }
}
