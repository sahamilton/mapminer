<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Exports\ExportOracleListData;
use App\Oracle;
use Excel;


class OracleList extends Component
{
    use WithPagination;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search = '';
    public $serviceline ='All';
    public $selectRole = 'All';
    public $showConfirmation=false;
    public $linked = 'yes';
    public $export = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingLinked()
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
    public function mount($role = null)
    {
        
        if($role) {
            $this->selectRole = $role;
            $this->linked = 'All';
        }
    }
    public function render()
    {
        return view(
            'livewire.oracle.oracle-list', 
            [
                'users'=>$this->_getUsers()->paginate($this->perPage),
                'roles'=>Oracle::distinct()->orderBy('job_profile')->get(['job_code', 'job_profile']),
                'links'=>['All'=>'All', 'no'=>'Not In Mapminer', 'yes'=>'In Mapminer'],
                 
             ]
         );
    
    }
    
    private function _getUsers()
    {
        return Oracle::query()
            ->with('mapminerUser.roles', 'mapminerManager.person')
            ->when(
                $this->selectRole != 'All', function ($q) {
                    $q->where('job_code', $this->selectRole);
                }
            )
            ->when(
                $this->linked != 'All', function ($q) {
                    $q->when(
                        $this->linked == 'yes', function ($q) {
                            $q->has('mapminerUser');
                        }, function ($q) {
                            $q->doesntHave('mapminerUser');
                        }
                    );  
                }
            )
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            
            /*->when(
                $this->export, function ($q) {
                    $q->get();
                }, function ($q) {
                    $q->paginate($this->perPage);
                }
            );*/
    }
    public function addUser(Oracle $oracle)
    {
        $oracle->load('oracleManager.mapminerUser.person');
        $data = [
            'user' => [
                'employee_id'=>$oracle->person_number,
                'email'=>$oracle->primary_email,
                ],
            'person' =>[
                'firstname'=>$oracle->first_name,
                'lastname'=>$oracle->last_name,
                'reports_to'=>$oracle->oracleManager->mapminerUser->person->id,
                'business_title'=>$oracle->job_profile,
                'country'=>$oracle->country,
                ],

                'other'=>[
                    // need to match this with the branch list
                    'location'=>$oracle->location_name,
                    // need to match this to the roles list
                    // // can we use Oracle job_code?
                    'role'=>$oracle->job_profile,
                ],
            ];
        
    }

    public function export()
    {
        
        $users = $this->_getUsers()->get();
       
        return Excel::download(
            new ExportOracleListData($users), 'oraclemapminerdata.csv'
        );
    }
};