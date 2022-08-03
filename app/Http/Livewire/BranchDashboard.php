<?php

namespace App\Http\Livewire;

use App\Branch;
use Livewire\Component;
use App\PeriodSelector;
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
    
    public function changeBranch($branch_id)
    {
         
         $this->branch_id = $branch_id;
         $this->emit('changeBranch', $this->branch_id);

    }

    public function changeView($view)
    {
         $this->view = $view;    
         

    }
    public function refreshChildren()
    {
        $this->emit('refreshChildren');

    }
    public function updatedBranchId()
    {
        
        $this->emit('refreshBranch', $this->branch_id);
        
    }
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
    private function _setBranch($branch=null)
    {
        
        if ($branch) {
            $this->branch_id = $branch;
        } else {
            $this->branch_id = array_key_first(auth()->user()->person->myBranches());
        }
    
    }

    private function _setMyBranches()
    {
        $myBranches = collect(auth()->user()->person->myBranches());
        
        if ($myBranches->count() >1) {
            $myBranches->prepend('All', 'all')->toArray();
        }
        return $myBranches;
    }
}
