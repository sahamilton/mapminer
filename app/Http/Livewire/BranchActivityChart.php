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

    public $view = 'period';
    
    public $myBranches;

    public $selectPeriod;

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
    public function mount(array $period, $branch_id=null)
    {
        
        $this->myBranches = auth()->user()->person->getMyBranches();

        if (! $branch_id) {
            $this->branch_id = 'all';

        } else {
            $this->branch_id = $branch_id;
        }
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

        if ($this->view === 'branch' && $this->branch_id != 'all') {
            $this->view = 'period';
        }
        @ray('72 Branch ID', $this->branch_id, $this->view);
        return view(
            'livewire.branch.branch-team-activity-chart',
            [
                'series'=>$this->_getSeries(),
                'categories'=> $this->_getCategories(),
                'title'=>$this->_getTitle(),
                'views'=>['team'=>'By Team', 'period'=>'By Period', 'branch'=>'By Branch'],


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
        $this->days = $this->period['from']->diff($this->period['to'])->days;
        if ($this->days > 30) {
            $this->selectPeriod = 'yearweek';
        } else {
            $this->selectPeriod = 'day';
        }
        
    }
    
    private function _getSeries()
    {
        
        
        switch($this->view) {

        case 'team':
            return $this->_getTeamSeries();
            break;


        case 'period':
            return $this->_getPeriodSeries();
            break;

        case 'branch':
            return $this->_getBranchData();

            break;
        }
    }


    private function _getCategories()
    {
        

        switch($this->view) {

        case 'team':
            return $this->_getTeamCategories();
            break;


        case 'period':
            return $this->_getPeriodCategories();
           
            break;

        case 'branch':
            return $this->_getBranchCategories();
            break;

        }
    }
    /**
     * [_getTeamCategories description]
     * 
     * @return [type] [description]
     */
    private function _getTeamCategories()
    {
        return Branch::with('branchTeam')->find($this->branch_id)->branchTeam->pluck('completeName')->toArray();
    }
    /**
     * [_getSeries description]
     * 
     * @return [type] [description]
     */
    private function _getTeamSeries()
    {
        if ($this->branch_id === 'all') {
            $branches = App\Branch::with('managers')
                ->whereIn('id', $myBranches)
                ->get(); 
            $managers = $branches->map(
                function ($branch) {

                    return $branch->managers->pluck('completename', 'id')->toArray();
                }
            ); 
            foreach ($managers as $mgr) {
                foreach ($mgr as $id=>$m) {
                    $team[$id]=$m;
                }
            }
        } else {
            $team = Branch::with('branchTeam')
                ->find($this->branch_id)
                ->branchTeam->pluck('completeName', 'id')
                ->toArray();
           

        }
        
        $data['categories'] =  $team;
        
        $fields = ActivityType::all();
        $result = Person::whereIn('persons.id', array_keys($team))
            ->summaryActivitiesByPerson($this->period)
            ->get();
      
        foreach ($fields as $field) {
  
            $data['series'][] = ['name' =>$field->activity,
            'data'=>$result->pluck($field->slug)->toArray(),
            'color'=> "#".$field->color];
  
        }

        return collect($data['series']);
       

    }

    /**
     * [_getTeamPeriodSeries description]
     * 
     * @return [type] [description]
     */
    private function _getBranchData()
    {

        $data['categories'] = Branch::when(
            $this->branch_id === 'all', function ($q) {
                $q->whereIn('branches.id', $this->myBranches);
            }, function ($q) {
                $q->where('branches.id', $this->branch_id);
            }
        )
        ->pluck('branchname')
        ->toArray();
        
        $fields = ActivityType::all();
        $result = Branch::summaryActivities(
            $this->period, $fields->pluck('activity', 'id')
                ->toArray()
        )
        ->when(
            $this->branch_id === 'all', function ($q) {
                $q->whereIn('branches.id', $this->myBranches);
            }, function ($q) {
                $q->where('branches.id', $this->branch_id);
            }
        )->get();
      
        foreach ($fields as $field) {
  
            $data['series'][] = ['name' =>$field->activity,
            'data'=>$result->pluck($field->slug)->toArray(),
            'color'=> "#".$field->color];
  
        }

        return collect($data['series']);
       

    }

    /**
     * [_getTeamCategories description]
     * 
     * @return [type] [description]
     */
    private function _getBranchCategories()
    {
        
        @ray('246 Branch Id', $this->branch_id);
        $data['categories'] = Branch::when(
            $this->branch_id === 'all', function ($q) {
                $q->whereIn('branches.id', $this->myBranches);
            }, function ($q) {
                $q->where('branches.id', $this->branch_id);
            }
        )
        ->pluck('branchname')
        ->toArray();
        return collect($data['categories']);
    }
    /**
     * [_getPeriodSeries description]
     * 
     * @return [type] [description]
     */
    private function _getPeriodSeries()
    {
        $activities = $this->_getRawPeriodActivityData();
       
            
        $typeids = $activities->map(
            function ($activity) {
                return $activity->activitytype_id;
            }
        );
        $typeids = array_unique($typeids->toArray());
        $fields = ActivityType::whereIn('id', $typeids)->get();
        $categories = $this->_getPeriodCategories();
        $data['series'] = $fields->map(
            function ($type) use ($activities, $categories) {

                $result=[];
                
                foreach ($categories as $day) {
                        $result[]=$activities
                            ->whereStrict('activitytype_id', $type->id)
                            ->whereStrict($this->selectPeriod, $day)->sum('activities');
                }

                return ['name'=>$type->activity, 'color'=>"#".$type->color, 'data'=>$result];


            }
        );

        return collect($data['series']);
    }

    private function _getPeriodCategories()
    {
        $activities = $this->_getRawPeriodActivityData();
        $labels = $activities->map(
            function ($activity) {
                if ($this->selectPeriod === 'yearweek') {

                    return $activity->yearweek;
                } else {
                     return $activity->day;

                }
                
                
                
            }
        );
        $labels = array_values(array_unique($labels->toArray()));
        //json_encode(array_values($arr)))
       
        return collect($labels);
        
    }

    private function _getRawPeriodActivityData()
    {
       
        $activities = Activity::when(
            $this->branch_id === 'all', function ($q) {
                $q->whereIn('branch_id', $this->myBranches);
            }, function ($q) {
                $q->where('branch_id', $this->branch_id);
            }
        )
        ->periodActivities($this->period)
        ->completed()
        ->when(
            $this->selectPeriod === 'yearweek', function ($q) {
                $q->sevenDayCount();
            }, function ($q) {
                $q->typeDayCount();
            }
        )->get();
        return $activities;
    }

    private function _getTitle()
    {
        if ($this->view == 'team') {
            return 'Branch Activities by Team Members for the period from ' 
            . $this->period['from']->format('Y-m-d') 
            . ' to ' . $this->period['to']->format('Y-m-d');
        } else {
            $period = $this->selectPeriod === 'yearweek' ? ' Week# ' : ' Day ' ;
            return 'Branch Activities by '  . ($period) . '  for the period from ' 
            . $this->period['from']->format('Y-m-d') 
            . ' to ' . $this->period['to']->format('Y-m-d');

        }
    }

}
