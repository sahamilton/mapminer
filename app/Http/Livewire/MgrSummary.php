<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;

use App\Branch;
use App\Address;
use App\Activity;
use App\ActivityType;
use App\Opportunity;


class MgrSummary extends Component
{
    
    use WithPagination, PeriodSelector;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'branchname';
    public $view='activities';
    public $setPeriod;
    public $filter = 'All';
    public $route;
    public array $views = [
            'activities', 
            'leads', 
            'opportunities'
        ];
    public $sortAsc = true;
    public $search ='';
    public $branch_id;
    public $myBranches;

   
    public array $fields=[];
    public array $activity_types;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingView()
    {
        $this->resetPage();
        $this->sortField='branchname';
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
        $this->myBranches = auth()->user()->person->myBranches();
        $this->branch_id = array_key_first($this->myBranches);
        $this->activity_types = ActivityType::pluck('slug', 'id')->toArray();
        $this->setPeriod = session('period')['period'];
    }
    

    public function render()
    {
        
        return view('livewire.mgr-summary',
            [
                'branches'=>$this->_getViewData(),
            ]

        );
    }


    private function _getViewData()
    {
        $this->_setPeriod();
        switch($this->view) {

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
                    ->summaryActivities($this->period, $this->fields)
                    ->search($this->search)
                    ->whereIn('id', array_keys($this->myBranches))
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);
                    
                
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
                $branches= Branch::query()
                    ->summaryLeadStats($this->period, $this->fields)
                    ->search($this->search)
                    ->whereIn('id', array_keys($this->myBranches))
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
                                "won_value"];
                $this->route = 'opportunities.branch';
                $branches= Branch::query()
                    ->summaryOpportunities($this->period, $this->fields)
                    ->search($this->search)
                    ->whereIn('id', array_keys($this->myBranches))
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);
            break;
        }

        return $branches;
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