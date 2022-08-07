<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Branch;
use App\PeriodSelector;
use App\Person;


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
    public $manager;
    public $displayFields;
    public $routes = [
        'summary'=>['branchdashboard.show'],
        'leads'=>['branch.leads'],
        'activities'=>['branch.activity'],
        'opportunities'=>['opportunities.branch']
        ];
    public $branch_id ='all';
    public $summaryview = 'summary';
    public $route;
    public $myBranches;

    
    public $fields =[
        'summary'=>[
                    "newbranchleads",
                    "active_leads",
                    'activities_count',
                    "new_opportunities",
                    "top25_opportunities",
                    "won_opportunities",
                    "won_value"
                ], 
        'activities'=>[

                    '4'=>'sales_appointment',
                    '5'=>'stop_by',
                    '7'=>'proposal',
                    '10'=>'site_visit',
                    '13'=>'log_a_call',
                    '14'=>'in_person',
                ],
        'leads'=>[
                'leads',
                'newbranchleads',
                'active_leads',
                'customer',
                'active_customer'
                ],
        'opportunities'=>[
                "active_opportunities",
                "active_value",
                "new_opportunities",
                "new_value",
                "open_opportunities",
                "open_value",
                "won_opportunities",
                "won_value"


                ]
            ];
    public $views =  [
                    'summary',
                    'activities', 
                    'leads', 
                    'opportunities'
                        ];
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
     * @param [type] $manager [description]
     * 
     * @return [type]          [description]
     */
    public function mount($manager)
    {
        
        $this->livewirePeriod(session('period.period'));
        $this->manager = Person::findOrFail($manager);
        $this->myBranches = $this->manager->myBranches();
        
        
       


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
                
                
            ]
        );
        
    }
    /**
     * [selectBranch description]
     * 
     * @param [type] $branch_id [description]
     * 
     * @return [type]            [description]
     */
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
    /**
     * [_getViewData description]
     * 
     * @return [type] [description]
     */
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
            ->get();
        $this->route = $this->routes[$this->summaryview];
        
        
        if (! $this->sortAsc) {
            return $branches->sortByDesc($this->sortField)->paginate($this->perPage);
        } else {
            return $branches->sortBy($this->sortField)->paginate($this->perPage);
        }
        
    }
}