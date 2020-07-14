<?php

namespace App\Http\Livewire;
use App\User;
use App\Role;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search = '';
    public $serviceline =false;
    public $selectRole = false;
    public $roles;

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
        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.user-table', 
            [
                'users' => User::query()
                ->select('users.*', 'persons.firstname', 'persons.lastname')
                ->join('persons', 'user_id', '=', 'users.id')
                ->with('usage',  'serviceline', 'roles')
                ->whereHas(
                        'roles',function($q) {
                            $q->when($this->selectRole, function ($q) {
                                $q->where('roles.id', $this->selectRole);
                            }
                        );
                    }

                )
                ->when(
                    $this->search, function ($q) {
                        $q->search($this->search);
                    }
                )
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]
       );
    }
}
