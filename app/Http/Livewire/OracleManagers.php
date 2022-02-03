<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Oracle;
use App\User;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

class OracleManagers extends Component
{
    use WithPagination;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search = '';
    public $type ='oracle';
    

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
        
        switch($this->type) {
        case 'oracle':
            return view(
                'livewire.oracle.oracle-managers',
                [
                    'users' => $this->_getUsers(),
                    'types' => $this->_getThisTypes(),
                ]
            );
            break;

        case 'mapminer':
            return view(
                'livewire.oracle.oracle-managers',
                [
                    'users' => Oracle::has('mapminerUser')
                        ->with('oracleManager', 'mapminerUser.roles', 'mapminerUser.person')
                        ->whereHas(
                            'oracleManager', function ($q) {
                                $q->doesntHave('mapminerUser');
                            }
                        )->search($this->search)
                        ->paginate($this->perPage),
                    'types' => $this->_getThisTypes(),
                    ]
            );
            break;

        case 'missing':
            return view(
                'livewire.oracle.oracle-managers',
                [   
                    'users'=>User::wherehas(
                        'person', function ($q) {
                            $q->doesntHave('reportsTo');
                        }
                    )
                    ->with('roles', 'oracleMatch.oracleManager')
                    ->search($this->search)
                    ->paginate($this->perPage),
                    'types' => $this->_getThisTypes(),
                ]
            );
            break;





        } 
        
    }

    private function _getUsers()
    {
        $users = Oracle::whereHas(
            'mapminerUser', function ($q) {
                $q->search($this->search);
            }
        )
        ->has('oracleManager.mapminerUser')
        ->search($this->search)
        ->with('oracleManager.mapminerUser.person', 'mapminerUser.person.reportsTo')
        ->get();

        return $users->filter(
            function ($user) {
                if ($user->mapminerUser->person->reportsTo->id != $user->oracleManager->mapminerUser->person->id) {
                    return $user;
                }
            }
        )->paginate($this->perPage);
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

    private function _getThisTypes()
    {
        return [
            'oracle'=>[
                'id'=>'oracle',
                'message'=>'Reassignable', 
                'description'=>'Manager in Oracle different than in Mapminer. Oracle manager in Mapminer', 
                'title'=>'Mapminer & Oracle'],
            'mapminer'=>[
                'id'=>'mapminer', 
                'message'=>'Not reassignable', 
                'description'=>'Manager in Mapminer different than in Oracle; Oracle manager not in Mapminer',
                'title'=>'Oracle & Mapminer'],
            'missing'=>[
                'id'=>'missing', 
                'message'=>'No Mapminer Manager', 
                'description'=>'No Mapminer manager',
                'title'=>'Oracle & Mapminer'],
        ];
    }
}
