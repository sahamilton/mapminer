<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Oracle;
use Livewire\WithPagination;

class OracleManagers extends Component
{
    use WithPagination;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search = '';
    

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
            'livewire.oracle.oracle-managers',
            [
                'users' => $this->_getUsers(),
            ]
        );
    }

    private function _getUsers()
    {
        $users = Oracle::has('mapminerUser')
            ->has('oracleManager.mapminerUser')
            ->with('oracleManager.mapminerUser.person', 'mapminerUser.person.reportsTo')->get();

        return $users->filter(
            function ($user) {
                if ($user->mapminerUser->person->reportsTo->id != $user->oracleManager->mapminerUser->person->id) {
                    return $user;
                }
            }
        );
    }
    public function rules()
    {
        return ['reports_to'=>'required'];
    }
    public function reassign(Oracle $oracle)
    {
        $oracle->load('mapminerUser.person', 'oracleManager.mapminerUser.person.reportsTo');
        $data =['reports_to'=>$oracle->oracleManager->mapminerUser->person->id];

        $oracle->mapminerUser->person->update($data);
        $message = $oracle->mapminerUser->person->fullName(). " has been reassigned to ".$oracle->oracleManager->mapminerUser->person->reportsTo->fullName();

        session()->flash('message', $message);
    }
    public function reassignAll()
    {
        $users = $this->_getUsers();
        foreach ($users as $user) {
            $this->reassign($user);
        }
    }
}
