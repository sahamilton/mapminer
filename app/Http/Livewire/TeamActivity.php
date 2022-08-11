<?php

namespace App\Http\Livewire;
use App\Person;
use App\Role;
use Livewire\Component;
use Livewire\WithPagination;

class TeamActivity extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'lastname';
    public $sortAsc = true;
    public $search ='';
    public $manager;

    public $role_id='All';


    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    /**
     * [sortBy description]
     * 
     * @param STR $field [description]
     * 
     * @return [type]        [description]
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function mount(Person $manager)
    {
        $this->manager = $manager;

    }



    public function render()
    {
        return view(
            'livewire.team-activity',
            [

                'team' => $this->_getTeamLogins(),
                'roles'=>Role::pluck('display_name', 'id')->prepend('All', 'All')->toArray(),

            ]
        );
    }


    private function _getTeamLogins()
    {

        return $this->manager->descendantsAndSelf()
            ->join('users', 'user_id', '=', 'users.id')
            ->search($this->search)
            ->withCount('userdetails.logins')
            ->select('*')
            ->with('userdetails.roles')
            ->when(
                $this->role_id != 'All', function ($q) {

                    $q->whereHas(
                        'userdetails.roles', function ($q) {
                            $q->whereIn('roles.id', [$this->role_id]);
                        }
                    );
                }
            )
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
  
           
    }
}
