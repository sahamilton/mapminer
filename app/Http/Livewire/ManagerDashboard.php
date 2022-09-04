<?php

namespace App\Http\Livewire;
use App\Models\Person;
use Livewire\Component;
use App\Models\Branch;
use App\Models\PeriodSelector;
use Livewire\WithPagination;
class ManagerDashboard extends Component
{
    use PeriodSelector, WithPagination;
    public $perPage = 10;
    public $sortField = 'branchname';
    public $activitytype='All';
    public $sortAsc = false;
    public $search ='';
    public $setPeriod='thisMonth';
    public $view = 'dashboard';
    public $branch_id;
    
    public $manager;
    public $paginationTheme = 'bootstrap';
    protected $listeners = ['changeBranch'];
    
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
     * @param  [type] $view [description]
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
     * @param int $manager [description]
     * 
     * @return [type]          [description]
     */
    public function mount(int $manager)
    {
        $this->manager = Person::findOrFail($manager);
        
        $this->_setMyBranches();
        $this-> _setPeriod();
        
         
        

    }

    
    /**
     * [render description]
     * 
     * @return view [description]
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
            'data'=>$this->_getData(),
            'myBranches'=>$this->_setMyBranches(),
            'viewtypes'=>[
                    'dashboard'=>'Dashboard', 

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
                    $q->where('id', array_keys($this->_getMyBranches));
                }
            )   
        ->first();
    }
    /**
     * [_setBranch description]
     * 
     * @param [type] $branch [description]
     *
     * @return set branch _id 
     */
    private function _setBranch($branch=null)
    {
        
        if ($branch) {
            /// that it is in my branches else send back first
            $this->branch_id = $branch;
        } else {
            $this->branch_id = $this->manager->myBranches($this->manager)->first();
        }
    
    }
    /**
     * [_setMyBranches description]
     *
     * @return array my branches
     */
    private function _setMyBranches()
    {
        $myBranches = collect($this->manager->myBranches($this->manager));
        
        if ($myBranches->count() >1) {
            $myBranches->prepend('All', 'all')->toArray();
        }
        return $myBranches;
    }
    /**
     * [_getData description]
     * 
     * @return [type] [description]
     */
    private function _getData()
    {

        if ($this->view !== 'dashboard') {
            return null;
        } else {
            $myBranches = $this->_setMyBranches()->toArray();

            $fields = ['4'=>'sales_appointment','won_opportunities','won_value'];
            $branches = Branch::whereIn('id', array_keys($myBranches))
                ->summaryStats($this->period, $fields)
                ->get();

            $string = '';
        
            foreach ($branches  as $branch) {
          
                $string = $string . "[\"".$branch->branchname ."\",  ".$branch->sales_appointment .",  ".$branch->won_opportunities.", ". ($branch->won_value ? $branch->won_value : 0) ."],";
             
            }
            $data['charts']['bubble'] = $string;

            if (! $this->sortAsc) {
                $data['branches'] = $branches->sortByDesc($this->sortField)->paginate($this->perPage);
            } else {
                $data['branches'] = $branches->sortBy($this->sortField)->paginate($this->perPage);
            }
            return $data;
        
        
        }
    }
}
