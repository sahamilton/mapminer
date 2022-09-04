<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Permission;

class RolesTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'id';
    
    public $sortAsc = true;
    public $search ='';
    
    public $paginationTheme = 'bootstrap';
    public $permission_id ='all';

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
            'livewire.roles-table', 
            [
                'roles'=>Role::with('permissions')
                ->when(
                    $this->permission_id != 'all', function ($q) {
                        $q->whereHas(
                            'permissions', function ($q) {
                                $q->where('permissions.id', $this->permission_id);
                            }
                        );
                    }
                )
                ->withCount('assignedRoles')
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'permissions'=>$this->_getPermissions(),


            ]
        );
    }

    private function _getPermissions()
    {
        $permissions = Permission::orderBy('display_name')->pluck('display_name', 'id')->toArray();
        $permissions['all'] = 'All';
        asort($permissions);
        return $permissions;
    }
}
