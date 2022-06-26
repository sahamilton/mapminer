<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Role;
use App\Permission;

class PermissionsTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'id';
    
    public $sortAsc = true;
    public $search ='';
    
    public $paginationTheme = 'bootstrap';
    public $role_id ='all';

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
        
    }
    public function render()
    {
        return view(
            'livewire.permissions-table', 
            [
                'permissions'=>Permission::with('roles')
                ->when(
                    $this->role_id != 'all', function ($q) {
                        $q->whereHas(
                            'roles', function ($q) {
                                $q->where('roles.id', $this->role_id);
                            }
                        );
                    }
                )
                
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'roles'=>$this->_getRoles(),


            ]
        );
    }

    private function _getRoles()
    {
    
        
        $roles = Role::orderBy('display_name')->pluck('display_name', 'id')->toArray();
        $roles['all'] = 'All';
        asort($roles);
        return $roles;
        
    }
}
