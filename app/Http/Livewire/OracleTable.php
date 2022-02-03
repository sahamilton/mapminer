<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;

use App\Oracle;
use App\User;
use App\Person;
use App\Role;
use App\Serviceline;
use Excel;
use App\Exports\ExportOracleData;


class OracleTable extends Component
{
    use WithPagination;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search = '';
    public $serviceline ='All';
    public $selectRole = 'All';
    public $showConfirmation=false;
    public $linked = 'no';
    public $roles;
    public $servicelines;
    public $links = [
        'All'=>'All', 
        'no'=>'Not In Oracle', 
        'yes'=>'In Oracle'
    ];

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
        $this->roles = Role::orderBy('display_name')->get();
        $this->servicelines = Serviceline::pluck('serviceline', 'id');

    }
    public function render()
    {
        return view(
            'livewire.oracle.oracle-table', 
            [
                'users'=>User::query()
                    ->with('usage', 'roles', 'person.reportsTo')
                    ->when(
                        $this->linked != 'All', function ($q) {
                            $q->when(
                                $this->linked == 'yes', function ($q) {
                                    $q->has('oracleMatch');
                                }, function ($q) {
                                    $q->doesntHave('oracleMatch');
                                }
                            );  
                        }
                    )
                    
                    ->when(
                        $this->serviceline != 'All', function ($q) {
                            $q->whereHas(
                                'serviceline', function ($q) {
                                    $q->whereIn('servicelines.id', [$this->serviceline]);
                                }
                            );
                        }
                    )->when(
                        $this->selectRole !='All', function ($q) {
                            $q->whereHas(
                                'roles', function ($q) {
                                    $q->when(
                                        $this->selectRole, function ($q) {
                                            $q->where('roles.id', $this->selectRole);
                                        }
                                    );
                                }
                            );
                        }
                    )
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                 
                 
                 
             ]
         );
   
    }
    public function export()
    {
               
        return Excel::download(
            new ExportOracleData(
                $this->selectRole,
                $this->linked, 
            ), 'oraclemapminerdata.csv'
        );
    }

    public function deleteSelected()
    {
        $ids = User::query()
            ->whereHas(
                'roles', function ($q) {
                    $q->when(
                        $this->selectRole, function ($q) {
                            $q->where('roles.id', $this->selectRole);
                        }
                    );
                }
            )
            ->whereHas(
                'person', function ($q) {
                    $q->doesntHave('directReports');
                }
            )
            ->doesntHave('oracleMatch')
            ->pluck('id')->toArray();
            // get branch associations
            // notify manager
            User::whereIn('id', $ids)->delete();
            Person::whereIn('user_id', $ids)->delete();

            session()->flash('message', count($ids) . " " . $this->roles->where('id', $this->selectRole)->first()->display_name."'s have been deleted");
            $this->selectRole="All";
    }
}

    

    


    