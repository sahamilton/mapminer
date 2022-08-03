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
    public $fields;
    public $branch_id;
    public $summaryview = 'summary';
    public $route;
    public $myBranches;

    protected $listeners = ['refreshBranch'=>'changeBranch', 'refreshPeriod'=>'changePeriod'];
    /**
     * [changeBranch description]
     * 
     * @param [type] $branch_id [description]
     * 
     * @return [type]            [description]
     */
    public function changeBranch($branch_id)
    {
         
         $this->branch_id = $branch_id;

    }


    /**
     * [changePeriod description]
     * 
     * @param [type] $setPeriod [description]
     * 
     * @return [type]            [description]
     */
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
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatedBranch()
    {
        $this->emit('changebranch', $this->branch_id);

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
        $this->myBranches = auth()->user()->person->myBranches();
        $this->branch_id = array_key_first($this->myBranches);


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
            'livewire.mgr-summary', [
                'branches'=>$this->_getViewData(),
                'views' => [
                    'summary',
                    'activities', 
                    'leads', 
                    'opportunities'
                        ],
            ]
        );
        
    }
    public function selectBranch($branch_id)
    {
        $this->branch_id = $branch_id;
        $this->emit('changeBranch', $branch_id);
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

    private function _getViewData()
    {
        $this->_setPeriod();
        switch($this->summaryview) {
        case 'summary':
            $this->fields =  [
                'newbranchleads',
                'touched_leads',
                'activities_count',
                'opened',
                'Top25',
                'won',
                'wonvalue',
            ];
            $branches =  Branch::query()
                ->summaryStats($this->period, $this->fields)
                ->when(
                    $this->branch_id != 'all', function ($q) {
                        $q->where('id', $this->branch_id);
                    }, function ($q) {
                         $q->whereIn('id', array_keys(auth()->user()->person->myBranches()));
                    }
                )
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate();
                $this->route = 'branchdashboard.show';

            break;
        case 'activities':
            
            $this->fields = [
                '4'=>'sales_appointment',
                '5'=>'stop_by',
                '7'=>'proposal',
                '10'=>'site_visit',
                '13'=>'log_a_call',
                '14'=>'in_person'
            ];
            $this->route = 'branch.activity';
            $branches= Branch::query()
                ->summaryStats($this->period, $this->fields)
                ->when(
                    $this->branch_id != 'all', function ($q) {
                        $q->where('id', $this->branch_id);
                    }, function ($q) {
                         $q->whereIn('id', array_keys(auth()->user()->person->myBranches()));
                    }
                )
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate();
            
            break;

        case 'leads':
            $this->fields = [
                'leads',
                'newbranchleads',
                'active_leads',
                'customer',
                'active_customer'
            ];
            $this->route = 'branch.leads';
            $branches = Branch::query()
                ->summaryStats($this->period, $this->fields)
                ->search($this->search)
                ->when(
                    $this->branch_id != 'all', function ($q) {
                        $q->where('id', $this->branch_id);
                    }, function ($q) {
                         $q->whereIn('id', array_keys(auth()->user()->person->myBranches()));
                    }
                )
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage);

            break;

        case 'opportunities':
            $this->fields = ["active_opportunities",
                            "active_value",
                            "new_opportunities",
                            "new_value",
                            "open_opportunities",
                            "open_value",
                            "won_opportunities",
                            "wonvalue"];
            $this->route = 'opportunities.branch';
            $branches= Branch::query()
                ->summaryStats($this->period, $this->fields)
                ->search($this->search)
                ->when(
                    $this->branch_id != 'all', function ($q) {
                        $q->where('id', $this->branch_id);
                    }, function ($q) {
                         $q->whereIn('id', array_keys(auth()->user()->person->myBranches()));
                    }
                )
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage);
            break;
        }

        return $branches;
    }
}