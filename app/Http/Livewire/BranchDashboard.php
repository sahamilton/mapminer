<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use Livewire\Component;
use App\Models\PeriodSelector;
use Livewire\WithPagination;


class BranchDashboard extends Component
{
    use WithPagination, PeriodSelector;

    public $perPage = 10;
    public $sortField = 'activity_date';
    public $activitytype='All';
    public $sortAsc = false;
    public $search ='';
    public $setPeriod='thisMonth';
    public $view = 'activities';
    public $branch_id;

    public $paginationTheme = 'bootstrap';
    protected $listeners = ['changeBranch'];
    

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
         $this->emit('changeBranch', $this->branch_id);

    }
    /**
     * [changeView description]
     * 
     * @param [type] $view [description]
     * 
     * @return [type]       [description]
     */
    public function changeView($view)
    {
         $this->view = $view;    
         

    }
    /**
     * [refreshChildren description]
     * 
     * @return [type] [description]
     */
    public function refreshChildren()
    {
        $this->emit('refreshChildren');

    }
    /**
     * [updatedBranchId description]
     * 
     * @return [type] [description]
     */
    public function updatedBranchId()
    {
        
        $this->emit('refreshBranch', $this->branch_id);
        
    }
    /**
     * [updatedSetPeriod description]
     * 
     * @return [type] [description]
     */
    public function updatedSetPeriod()
    {
        
        $this->emit('refreshPeriod', $this->setPeriod);
        
    }
    /**
     * [mount description]
     * 
     * @param string|null $branch [description]
     * @param string|null $status [description]
     * 
     * @return [type]         [description]
     */
    public function mount($branch)
    {
       
        $this->_setBranch($branch->id);
        $this-> _setPeriod();
        
         
        

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
            'livewire.branch.branch-dashboard', [
            'branch'=>$this->_getBranch(),
            'timeperiods'=>[
                        
                        'allDates'=>'All',
                        'thisWeek'=>'This Week',
                       
                        'lastWeek'=>'Last Week',
                        'thisMonth'=>'This Month',
                       
                        'lastMonth'=>'Last Month',
                        'thisQuarter'=>'This Quarter',
                      
                        'lastQuarter'=>'Last Quarter',
                        'lastSixMonths'=>'Last Six Months',

                    ],

            'myBranches'=>$this->_setMyBranches(),
            'viewtypes'=>[
                    'summary'=>'Summary', 
                    'activities'=>'Activities', 
                    'charts'=>'Charts',
                    'team'=>'Team'
                    ],
            ]
        );
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
     * [_getBranch description]
     * 
     * @return [type] [description]
     */
    private function _getBranch()
    {
        
       
        return Branch::query()
            ->when(
                $this->branch_id !=='all', function ($q) {
                    $q->where('id', $this->branch_id);
                }, function ($q) {
                    $q->where('id', 1822);
                }
            )   
        ->first();
    }
    /**
     * [_setBranch description]
     * 
     * @param [type] $branch [description]
     *
     * @return _setbranch   
     */
    private function _setBranch($branch=null)
    {
        
        if ($branch) {
            $this->branch_id = $branch;
        } else {
            $this->branch_id = array_key_first(auth()->user()->person->myBranches());
        }
    
    }
    /**
     * [_setMyBranches description]
     *
     * @return array
     */
    private function _setMyBranches() : \Illuminate\Support\Collection
    {
        $myBranches = collect(auth()->user()->person->myBranches());
        
        if ($myBranches->count() >1) {
            $myBranches->prepend('All', 'all')->toArray();
        }
        return $myBranches;
    }
}
