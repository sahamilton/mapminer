<?php

namespace App\Http\Livewire;
use App\Oracle;
use App\User;
use Livewire\Component;
use Livewire\WithPagination;
class OracleVerify extends Component
{
    use WithPagination;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'last_name';
    public $sortAsc = false;
    public $search = '';
    public User $user;
    public $person_number;

    public $showConfirmation=false;
    public $linked = 'no';

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
        return view(
            'livewire.oracle.oracle-verify',
            [
                'users' => Oracle::join('users', 'primary_email', '=', 'email')
                    ->whereNull('users.deleted_at')
                    ->doesntHave('mapminerUser')
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
            ]
        );
    }
    public function rules() 
    {
        return [

            

        ];
    }
    public function updateEmployeeNumber(User $user, $person_number)
    {
        
        $user->update(['employee_id'=>$person_number]);
    }
}
