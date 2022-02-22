<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Oracle;
use App\User;

class ManageTeam extends Component
{
    
    use WithPagination;
    
    public User $user;
    public $perPage = 10;
    public $sortField = 'last_name';
    public $sortAsc = true;
    public $search = '';
    public $paginationTheme = 'bootstrap';
   
  
  

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
    public function mount($user=null)
    {
        if ($user && auth()->user()->hasRole(['admin'])) {
            $this->user = $user->load('person.reportsTo');
        } else {
            $this->user = auth()->user()->load('person.reportsTo');
        }
      
    }

    public function render()
    {
        return view(
            'livewire.manage-team',
            [
                'team' => Oracle::whereHas(
                    'oracleManager', function ($q) {
                        $q->where('person_number', $this->user->employee_id);
                    }
                )
                
                ->with('mapminerUser.roles', 'mapminerUser.person.branchesServiced', 'oracleManager')
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),

            ]
        );
    }
}
