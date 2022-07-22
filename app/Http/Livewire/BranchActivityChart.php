<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Activity;
use App\PeriodSelector;
use Carbon\CarbonPeriod;

class BranchActivityChart extends Component
{
    use PeriodSelector;
    public $setPeriod;
    
    public $branch_id;

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
     * [mount description]
     * 
     * @param int    $branch_id [description]
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
            'livewire.branch-activity-chart',
            [
                'labels'=>$this->_getLabels(),
                'values'=> $this->_getValues(),



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
        $this->startdate = $this->period['from']->format('Y-m-d');     
        
    }
    /**
     * [_getLabels description]
     * 
     * @return [type] [description]
     */
    private function _getLabels()
    {
        

        $period = CarbonPeriod::create($this->period['from'], $this->period['to'])->toArray();
        foreach ($period as $day) {
            $labels[] = $day->format('Y-m-d');
        }
        return $labels;
       
        
    }
    /**
     * [_getValues description]
     * 
     * @return [type] [description]
     */
    private function _getValues()
    {
        

        $activities = Activity::where('branch_id', $this->branch_id)
            ->periodActivities($this->period)
            ->completed()
            ->typeDayCount()
            ->get();
           
        $days = $this->_getLabels();
        foreach ($days as $day) {
            $values[] = $activities->where('day', $day)->sum('activities');
        }
        return $values;


    }

}
