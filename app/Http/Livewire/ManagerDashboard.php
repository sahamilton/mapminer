<?php

namespace App\Http\Livewire;
use App\Person;
use Livewire\Component;

use App\PeriodSelector;

class ManagerDashboard extends Component
{
    use PeriodSelector;
    public $perPage = 10;
    public $sortField = 'activity_date';
    public $activitytype='All';
    public $sortAsc = false;
    public $search ='';
    public $setPeriod='thisMonth';
    public $view = 'summary';
    public $branch_id;
    
    public $manager;
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
    public function mount($manager)
    {
        $this->manager = Person::findOrFail($manager);
        
        $this->_setMyBranches();
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
            'livewire.manager-dashboard', [
            
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
                    
                    'charts'=>'Charts',
                    
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
            /// that it is in my branches else send back first
            $this->branch_id = $branch;
        } else {
            $this->branch_id = $this->manager->myBranches($this->manager)->first();
        }
    
    }

    private function _setMyBranches()
    {
        $myBranches = collect($this->manager->myBranches($this->manager));
        
        if ($myBranches->count() >1) {
            $myBranches->prepend('All', 'all')->toArray();
        }
        return $myBranches;
    }

}
