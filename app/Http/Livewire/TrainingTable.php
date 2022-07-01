<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Training;
use App\Role;

class TrainingTable extends Component
{
    use WithPagination;
    
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search = '';
    public $selectRole = 'All';
    public $status = 'All';
    public array $statuses =Training::STATUSES;
    public $openForm = false;

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
        
      
    }
    public function render()
    {
        return view('livewire.training-table', 
            [
            'trainings'=>Training::query()
                ->with('relatedRoles')
                ->when(
                    $this->selectRole !='All', function ($q) {
                        $q->whereHas(
                            'relatedRoles', function ($q) {
                                
                                $q->where('roles.id', $this->selectRole);
                                    
                            }
                        );
                    }
                )
                ->when(
                    $this->status !='All', function ($q) {
                        $q->when($this->status ==='current', function ($q) {
                            
                                $q->current();
                                    
                            }
                        );
                         $q->when($this->status ==='closed', function ($q) {
                            
                                $q->closed();
                                    
                            }
                        );
                    }
                )
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'roles'=>$this->_getRoles(),
            ]
        );
    }
    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        $this->openForm = true;
    }
    /**
     * [_getRoles description]
     * @return [type] [description]
     */
    private function _getRoles()
    {
        $this->roles = Role::all()->pluck('display_name', 'id')->toArray();
        $this->roles['All'] = ' All';
        asort($this->roles);
    }
}
