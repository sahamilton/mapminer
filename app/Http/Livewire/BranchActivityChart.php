<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Activity;
use App\ActivityType;
use App\Branch;
use App\PeriodSelector;
use App\Person;
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
                'series'=>$this->_getSeries(),
                'categories'=> $this->_getCategories(),



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
    

    private function _getCategories()
    {
        return Branch::with('branchTeam')->find($this->branch_id)->branchTeam->pluck('completeName')->toArray();
    }
    /**
     * [_getSeries description]
     * 
     * @return [type] [description]
     */
    private function _getSeries()
    {

        $team = Branch::with('branchTeam')
            ->find($this->branch_id)
            ->branchTeam->pluck('id')
            ->toArray();
        
        $fields = ActivityType::all();
        $result = Person::whereIn('persons.id', $team)
            ->summaryActivitiesByPerson($this->period)
            ->get();
        $data = [];
        foreach ($fields as $field) {
  
            $data[] = ['name' =>$field->activity,
            'data'=>$result->pluck($field->slug)->toArray(),
            'color'=> "#".$field->color];
  
        }
        
        return collect($data)->toArray();
       

    }

}
