<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Exports\ExportOracleData;
use App\Oracle;



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

    public function render()
    {
        return view(
            'livewire.oracle.oracle-list', 
            [
                'users'=>Oracle::query()
                    ->with('mapminerUser', 'mapminerManager.person')
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
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'roles'=>Oracle::distinct()->orderBy('job_profile')->get(['job_code', 'job_profile']),
                'links'=>['All'=>'All', 'no'=>'Not In Mapminer', 'yes'=>'In Mapminer'],
                 
             ]
         );
    
    }

    public function export()
    {
               
        return Excel::download(
            new ExportOracleData(
                $this->linked, 
                $this->selectRole
            ), 'oraclemapminerdata.csv'
        );
    }
};