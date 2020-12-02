<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Branch;
use App\Region;

class BranchTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'id';
    public $state='All';
    public $region='All';
    public $sortAsc = true;
    public $search ='';
    public $userServiceLines;



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
        
        $this->userServiceLines = auth()->user()->load('serviceline')->serviceline->pluck('id')->toArray();
    }
    public function render()
    {
        
        return view(
            'livewire.branch-table', [
                'branches'=>Branch::query()
                    ->with(
                        'region', 
                        'manager', 
                        'relatedPeople.userdetails.roles', 
                        'servicelines'
                    )
                    ->when(
                        $this->state != 'All', function ($q) {
                                $q->where('state', $this->state);
                        }
                    )
                ->when(
                    $this->region != 'All', function ($q) {
                        $q->where('region_id', $this->region);
                    }
                )
            ->whereHas(
                'servicelines', function ($q) {
                        $q->whereIn('serviceline_id', $this->userServiceLines);

                }
            )
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage),
            'allstates' => Branch::select('state')
                ->distinct('state')
                
                ->when(
                    $this->region != 'All', function ($q) {
                            $q->where('region_id', $this->region);
                    }
                )
                ->orderBy('state')
                ->get(),
            'regions' => Region::select('id', 'region')->has('branches')->orderBy('region')->get(),
               
            


            ]
        );
        
    }
}
