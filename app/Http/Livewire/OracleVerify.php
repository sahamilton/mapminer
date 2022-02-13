<?php

namespace App\Http\Livewire;
use App\Oracle;
use App\User;
use App\Role;
use Livewire\Component;
use Livewire\WithPagination;
use DB;
class OracleVerify extends Component
{
    use WithPagination;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'person';
    public $sortAsc = true;
    public $search = '';
    public User $user;
    public $person_number;
    public $showView ='roles';
    public Array $views = ['emails', 'roles'];
    public $showConfirmation=false;
    public $linked = 'no';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingView()
    {
        $this->resetPage();
        switch($this->showView) {
            case 'emails':
            $this->sortField = 'last_name';
            break;

            case 'roles':
            $this->sortField = 'person';
            break;
        }
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
        switch ($this->showView) {
            case 'emails':
                return view(
                    'livewire.oracle.oracle-verify',
                    [
                        'users' => Oracle::join('users', 'primary_email', '=', 'email')
                            ->whereNull('users.deleted_at')
                            ->doesntHave('mapminerUser')
                            ->search($this->search)
                            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                            ->paginate($this->perPage),
                            'title'=>'Matched Email but Unmatched Employee #',
                    ]
                );

            break;

            case 'roles':
                return view(
                    'livewire.oracle.oracle-verify',
                    [
                        'users' => $this->_mismatchedRoles()
                        ->paginate($this->perPage),
                        'title' => "Mismatched Role between Oracle & Mapminer",
                    ]
                );
            break;
        }
        
        
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

    public function updateEmployeeRole(User $user, Role $role)
    {
        $user->roles()->sync($role);
        session()->flash('message', $user->fullName() . ' has been reassigned to ' . $role->display_name);
    }


    private function _mismatchedRoles()
    {
        
        $sql ="select 
            concat_ws(' ', p.firstname, p.lastname) as person,
            p.id as personId,
            users.id as userId,
            oracle.manager_name as oraclemanager, 
            concat_ws(', ', pm.lastname, pm.firstname) as mapminermanager,
            pm.id as manager_id,
            m.id as MMRoleId, 
            m.display_name as MMRole, 
            oracle_jobs.job_profile as profile, 
            oracle_jobs.job_code, 
            oracle_jobs.role_id as OracleRoleID,
            o.display_name as OracleRole,
            lastlogin
            from users, persons p, persons pm, role_user, roles m, roles o, oracle, oracle_jobs 
            where users.id = role_user.user_id 
            and role_user.role_id = m.id 
            and users.employee_id = oracle.person_number 
            and oracle.job_code = oracle_jobs.job_code 
            and oracle_jobs.role_id != m.id 
            and oracle_jobs.role_id = o.id
            and users.id = p.user_id 
            and users.deleted_at is null
            and p.reports_to = pm.id
            and pm.deleted_at is null
            order by " . $this->sortField;
        $sql = $sql . ($this->sortAsc ? ' asc ' :  ' desc');

        return collect(DB::select(DB::raw($sql)));
        
    }

    
}
