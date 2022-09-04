<?php

namespace App\Http\Livewire;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\OracleJobs;
class OracleJoblist extends Component
{
    use WithPagination;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'job_profile';
    public $sortAsc = true;
    public $search = '';
    public $serviceline ='All';
    public $selectRole = 'All';
    public $showConfirmation=false;
    public $linked = 'yes';
    public $export = false;
    public $select = 'All';

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
            'livewire.oracle.oracle-joblist', 
            [
                'jobs'=>OracleJobs::with('mapminerRole')
                    ->when(
                        $this->select != 'All', function ($q) {
                            $q->when(
                                $this->select == 'With', function ($q) {
                                    $q->has('mapminerRole');
                                }, function ($q) {
                                        $q->doesntHave('mapminerRole');
                                }
                            );
                        }
                    )
                    ->withCount('oracleJob')
                    ->withCount(
                        [
                            'oracleJob as MapminerUser'=>function ($q) {
                                $q->has('mapminerUser');
                            }
                        ]
                    )
                    ->withCount(
                        [
                            'oracleJob as NotMapminerUser'=>function ($q) {
                                $q->doesntHave('mapminerUser');
                            }
                        ]
                    )
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                    'options'=>['All', 'Without', 'With'],
                
                 
             ]
         );
    
    }
    
    
}
