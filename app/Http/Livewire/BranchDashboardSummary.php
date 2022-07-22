<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Branch;
use App\PeriodSelector;



class BranchDashboardSummary extends Component
{
    use WithPagination, PeriodSelector;

    public $perPage = 10;
    public $sortField = 'id';
    public $sortAsc = true;
    public $search ='';
    public $setPeriod;
    public $serviceline = 'All';
    public $userServiceLines;
    public $paginationTheme = 'bootstrap';
    public $manager = 'All';
    public array $fields = [
                'newbranchleads',
                'touched_leads',
                'activities_count',
                'opened',
                'Top25',
                'won',
                'lost',
            ];
    public $branch_id;

    protected $listeners = ['refreshBranch'=>'changeBranch', 'refreshPeriod'=>'changePeriod'];
   
    public function changeBranch($branch_id)
    {
         
         $this->branch_id = $branch_id;

    }
    public function changePeriod($setPeriod)
    {
        
        $this->setPeriod = $setPeriod;
    }
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
     * @param string $field [description]
     * 
     * @return string        [description]
     */
    public function sortBy(string $field)
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
     * @param string $branch_id [description]
     * @param array  $period    [description]
     * 
     * @return [type]            [description]
     */
    public function mount(int $branch_id, array $period)
    {
        
        $this->branch_id = $branch_id;
        $this->setPeriod = $period['period'];


    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->_setPeriod();
        
        return view(
            'livewire.branch-summary', [
                'branches'=>Branch::query()
                    ->summaryStats($this->period, $this->fields)
                    ->when(
                        $this->branch_id != 'all', function ($q) {
                            $q->where('id', $this->branch_id);
                        }, function ($q) {
                             $q->whereIn('id', array_keys(auth()->user()->person->myBranches()));
                        }
                    )
                    
                    ->get(),

            ]
        );
        
    }
    public function selectBranch($branch_id)
    {
        $this->branch_id = $branch_id;
        $this->emitUp('changeBranch', $branch_id);
    }
    /**
     * [_setPeriod description]
     *
     * @return setPeriod
     */
    private function _setPeriod()
    {
        
        $this->livewirePeriod($this->setPeriod);
            
        
    }
}