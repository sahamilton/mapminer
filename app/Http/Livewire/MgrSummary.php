<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeriodSelector;

use App\Models\Branch;
use App\Models\Address;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Opportunity;
use App\Models\Person;

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
        public array $displayfields;
    public $sortAsc = true;
    public $search ='';
    public $branch_id;
    public $myBranches;
    public $summaryview = 'summary';
    public $manager;
   
    public array $fields=[];
    public array $activity_types;

    protected $listeners = ['changeBranch', 'refreshPeriod'=>'changePeriod'];

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
    public function mount($manager=null)
    {
     
        if (! $manager) {
            $this->myBranches = auth()->user()->person->myBranches($this->manager);
        } else {
            @ray('hrererere');
            $this->manager = Person::findOrFail($manager);
            $this->myBranches = $this->manager->myBranches($this->manager);
        }
     
        $this->branch_id = array_key_first($this->myBranches);
        $this->activity_types = ActivityType::pluck('slug', 'id')->toArray();
        if (! session()->has('period')) {
            $this-> _setPeriod();
        } 
        $this->setPeriod = session('period')['period'];
    }
    

    public function render()
    {
        
        return view(
            'livewire.mgr-summary',
            [
                'branches'=>$this->_getViewData(),
            ]
        );
    }


    private function _getViewData()
    {
        $this->_setPeriod();
       
        $this->displayFields = $this->fields[$this->summaryview];
        $branches =  Branch::query()
            ->summaryStats($this->period, $this->displayFields)
            ->when(
                $this->branch_id != 'all', function ($q) {
                    $q->where('id', $this->branch_id);
                }, function ($q) {
                     $q->whereIn('id', array_keys(auth()->user()->person->myBranches()));
                }
            )
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate();
        $this->route = $this->routes[$this->summaryview];
        
        /*switch($this->summaryview) {
        case 'summary':
            $this->displayfields =  [
                'newbranchleads',
                'active_leads',
                'activities_count',
                'new_opportunities',
                'won_opportunities',
                'won_value',
            ];
            @ray($this->sortField);
            $branches =  Branch::query()
                ->summaryStats($this->period, $this->fields)
                ->whereIn('id', array_keys($this->myBranches))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate();
                $this->route = 'branchdashboard.show';

            break;
        case 'activities':
            
            $this->displayfields = [
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
            $this->displayfields = [
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
            $this->displayfields = ["active_opportunities",
                            "active_value",
                            "new_opportunities",
                            "new_value",
                            "open_opportunities",
                            "open_value",
                            "won",
                            "wonvalue"];
            $this->route = 'opportunities.branch';
            $branches= Branch::query()
                ->summaryOpportunities($this->period, $this->fields)
                ->search($this->search)
                ->whereIn('id', array_keys($this->myBranches))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage);
            break;
        }*/
        
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
