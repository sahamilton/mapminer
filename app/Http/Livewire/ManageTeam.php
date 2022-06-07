<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Oracle;
use App\User;

class ManageTeam extends Component
{
    
    use WithPagination;
    public $user_id;
    public User $user;
    public $perPage = 10;
    public $sortField = 'last_name';
    public $sortAsc = true;
    public $search = '';
    public $paginationTheme = 'bootstrap';
    public array $job_codes;
    public $role = 'all';
  

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
        
        $this->user = $user;
        $this->job_codes = $this->_getJobCodes();
        
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
                ->leftJoin('users', 'oracle.person_number', '=', 'users.employee_id')
                ->when(
                    $this->role !='all', function ($q) {
                        $q->where('job_code', $this->role);
                    }
                )
                ->with('mapminerUser.roles', 'mapminerUser.person.branchesServiced', 'oracleManager')
                ->select('oracle.*', 'users.lastlogin') 
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),

            ]
        );
    }

    public function changeUser(User $user)
    {
        $this->user = $this->_validateUser($user);
    }

    private function _validateUser(User $user)
    {
        $myTeam = auth()->user()->person->descendants()->pluck('user_id')->toArray();
        if ($user && auth()->user()->hasRole(['admin'])) {
            return $user->load('person.reportsTo');
        } elseif (in_array($user->id, $myTeam)) {
            return $user->load('person.reportsTo');   
        } else {
            return auth()->user()
                ->load('person.reportsTo');
        }
    }

    private function _getJobCodes()
    {
        $job_codes = Oracle::whereHas(
            'oracleManager', function ($q) {
                $q->where('person_number', $this->user->employee_id);
            }
        )->pluck('job_profile', 'job_code')
        ->unique()
        ->toArray();
        $job_codes['all']='All';
        return $job_codes;
    }
}
