<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Location;

class BranchTable extends Component
{
    use WithPagination, NearbyGeocoder;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'id';
    public $state='All';
    public $region='All';
    public $distance = '25';
    public $sortAsc = true;
    public $search ='';
    public $status = 'no';
    public $serviceline = 'All';
    public $userServiceLines;
    
    public $manager = 'All';
    public Location $location;
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
     * [updatingDistance description]
     * 
     * @return [type] [description]
     */
    public function updatingDistance()
    {
        if ($this->distance === 'all') {
            $this->state='All';
        }
    }
    /**
     * [sortBy description]
     * 
     * @param [type] $field [description]
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
    /**
     * [restoreBranch description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function restoreBranch($branch_id)
    {
        $branch = Branch::withTrashed()->find($branch_id);
        $branch->deleted_at=null;
        $branch->save();
    }
    /**
     * [mount description]
     * 
     * @param [type] $state [description]
     * 
     * @return [type]        [description]
     */
    public function mount($state = null)
    {
        if (isset($state)) {
            $this->state = $state;
            $this->distance = 'all';
        }
        $this->userServiceLines = auth()->user()->currentServiceLineIds();
        $this->_geoCodeHomeAddress();
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->updateAddress();
        return view(
            'livewire.branch.branch-table', [
                'branches'=>Branch::query()
                    ->with(
                        'region', 
                        'manager', 
                        'relatedPeople.userdetails.roles', 
                        'servicelines'
                    )
                    ->when(
                        $this->status != 'no', function ($q) {
                            $q->when(
                                $this->status=='yes', function ($q) {
                                    $q->onlyTrashed();
                                }, function ($q) {
                                    $q->withTrashed();
                                }
                            );
                        }
                    )
                    ->when(
                        $this->manager != 'All', function ($q) {
                            $q->when(
                                $this->manager == 'with', function ($q) {
                                    $q->whereHas('manager');
                                }, function ($q) {
                                    $q->whereDoesntHave('manager');
                                }
                            );
                            
                        }
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
                    ->when(
                        $this->serviceline != 'All', function ($q) {
                            $q->whereHas(
                                'servicelines', function ($q) {
                                    $q->where('serviceline_id', $this->serviceline);

                                }
                            );
                        }, function ($q) {
                            $q->whereHas(
                                'servicelines', function ($q) {
                                    $q->whereIn('serviceline_id', array_keys($this->userServiceLines));

                                }
                            );
                        }
                    )
                    ->when(
                        $this->distance != 'all', function ($q) {
                            $q->nearby($this->location, $this->distance);
                        }, function ($q) {
                            $q->distanceTo($this->location);
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
            'distances'=>['all'=>'All', 5=>5,10=>10,25=>25, 50=>50,100=>100],
            'statuses'=>['yes'=>'Trashed', 'no'=>'Active', 'both'=>'Both']   ,

            ]
        );
        
    }
    
   
}
