<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\PeriodSelector;
use App\Oracle;
use App\User;
use App\Role;
use App\Serviceline;


class OracleTable extends Component
{
    use WithPagination, PeriodSelector;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search = '';
    public $serviceline ='All';
    public $selectRole = false;
    public $status = 'current';
    public $showConfirmation=false;

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
            'livewire.oracle-table', 
            [
                'users'=>User::query()
                    ->select('users.*', 'persons.firstname', 'persons.lastname')
                    ->join('persons', 'user_id', '=', 'users.id')
                    ->with('usage', 'roles')
                    ->doesntHave('oracleMatch')
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                 'statuses'=>['all', 'deleted', 'current'],
                 'roles'=>Role::all(),
                 'servicelines'=>Serviceline::pluck('serviceline', 'id'),
             ]
         );
   
    }
}

    

    


    