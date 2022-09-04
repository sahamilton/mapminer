<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Leadsource;

use App\Models\Branch;


class LeadSourceTable extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'source';
    public $sortAsc = true;
    public $search = null;
    public $stale = 6;
    public $branch_id = 'All';
    public $type= 'All';
    //public $myBranches;
   
 
    public function updatingSearch()
    {
        $this->resetPage();
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
     * [mount description]
     * 
     * @return [type]         [description]
     */
    public function mount()
    {
       
        //$this->myBranches = auth()->user()->person->myBranches();
        //$this->branch_id = array_key_first($this->myBranches);
        
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        
        return view(
            'livewire.lead-source-table', 
            [
               
                'leadsources' => Leadsource::query()
                    ->when(
                        $this->type !='All', function ($q) {
                            $q->whereType($this->type);
                        }
                    )
                    ->withCount(
                        [
                            'branchleads'=>function ($q) {
                                $q->when(
                                    $this->branch_id != 'All', function ($q) {
                                        $q->whereIn('address_branch.branch_id', [$this->branch_id]);
                                    }
                                );
                            }  
                        ] 
                    )
                    ->withCount(
                        [
                            'branchleads as staleleads'=>function ($q) {
                                $q->when(
                                    $this->branch_id != 'All', function ($q) {
                                        $q->whereIn('address_branch.branch_id', [$this->branch_id]);
                                    }
                                )->where(
                                    function ($q) {
                                        $q->where('last_activity', '<=', now()->subMonth($this->stale))
                                            ->orWhereNull('last_activity');
                                    }
                                );
                            }  
                        ] 
                    )
                    
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),

                    'branch'=>Branch::find($this->branch_id),
                    'types'=>LeadSource::selectRaw('distinct type as type')
                        ->orderBy('type')
                        ->pluck('type')
                        ->toArray(),
                            
            ]
        );
    }

    /**
     * [export description]
     * 
     * @return [type] [description]
     */
    public function export()
    {



    }
    
}