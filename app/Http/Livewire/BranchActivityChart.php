<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Activity;
use App\ActivityType;
use App\Branch;
use App\PeriodSelector;
use App\Person;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection; 

class BranchActivityChart extends Component
{
    use PeriodSelector;
    public $setPeriod;
    
    public $branch_id;

    public $view = 'period';
    
    public $myBranches;

    public $selectPeriod;

    public $fields;

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
        $this->fields = ActivityType::all();
        
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
       
        return view(
            'livewire.branch.branch-team-activity-chart',
            [
                'summary'=>$this->_getData(),
                'series'=>$this->_getSeries(),
                'categories'=> $this->_getCategories(),
                'title'=>$this->_getTitle(),
                'views'=>$this->_getViews(),


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
    /**
     * [_getData description]
     * 
     * @return [type] [description]
     */
    private function _getData()
    {
        switch ($this->view){

        case 'team':
            $team =  $this->_getTeam()->pluck('id')->toArray();
            
            return Person::whereIn('persons.id', $team)->summaryActivities($this->period)->get();
            break;

        case 'branch':
           
            return Branch::summaryActivities($this->period, $this->fields->pluck('activity', 'id')->toArray())
            ->when(
                $this->branch_id === 'all', function ($q) {
                    $q->whereIn('branches.id', $this->myBranches);
                }, function ($q) {
                    $q->where('branches.id', $this->branch_id);
                }
            )->get();

            break;

        case 'period':
            return  Activity::when(
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
            )->orderBy('activity_date')
            ->get();
        

            break;


        }

    }
    /**
     * [_getSeries description]
     * 
     * @return [type] [description]
     */
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

    /**
     * [_getCategories description]
     * 
     * @return [type] [description]
     */
    private function _getCategories()
    {
        

        switch($this->view) {

        case 'team':
            return $this->_getTeamCategories();
            break;


        case 'period':
            /* if ($this->selectPeriod == 'yearweek') {
                $categories = $this->_getPeriodCategories(); 
                return $this->_getYearWeekDate($categories);
            } else {
                return $this->_getPeriodCategories();
            }*/
            return $this->_getPeriodCategories();
            break;

        case 'branch':
            return $this->_getBranchCategories();
            break;

        }
    }
    /**
     * [_getTeam description]
     * 
     * @return [type] [description]
     */
    private function _getTeam()
    {
        if ($this->branch_id != 'all') {
                return Branch::with('branchTeam')->find($this->branch_id)->branchTeam;
        } else {
            $branches = Branch::with('branchTeam')->whereIn('branches.id', $this->myBranches)->get();
            return $branches->flatMap(
                function ($branch) {
                    return $branch->branchTeam;
                }
            )->unique('id');
        }
    }
    /**
     * [_getTeamCategories description]
     * 
     * @return [type] [description]
     */
    private function _getTeamCategories()
    {
        return collect($this->_getTeam()->pluck('completename')->toArray());
       
    }
    /**
     * [_getSeries description]
     * 
     * @return [type] [description]
     */
    private function _getTeamSeries()
    {
        $data['categories'] = $this->_getTeam();
            
        
        $result = $this->_getData();
       
        foreach ($this->fields as $field) {
            
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
        
        
        $result= $this->_getData();

        foreach ($this->fields as $field) {
  
            $data['series'][] = ['name' =>$field->activity,
            'data'=>$result->pluck($field->slug)->toArray(),
            'color'=> "#".$field->color];
  
        }

        return collect($data['series']);
       

    }

    /**
     * [_getBranchCategories description]
     * 
     * @return [type] [description]
     */
    private function _getBranchCategories()
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
        return collect($data['categories']);
    }
    /**
     * [_getPeriodSeries description]
     * 
     * @return [type] [description]
     */
    private function _getPeriodSeries()
    {
        
        $fields = ActivityTYpe::all();
        $activities = $this->_getData();
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
        )->orderBy('activity_date')
        ->get();
        return $activities;
    }
    /**
     * [_getViews description]
     * 
     * @return [type] [description]
     */
    private function _getViews()
    {
        $views['period'] = 'By Period';
        if (count($this->myBranches) >1) {
            $views['branch']= 'By Branch';
        }
        if (auth()->user()->person->directReports()->count() > 0) {
            $views['team']= 'By Team';
        }

        return $views;
    }
    /**
     * [_getTitle description]
     * 
     * @return [type] [description]
     */
    private function _getTitle()
    {
        if ($this->view == 'team') {
            return ucwords($this->branch_id) .' Branch Activities by Team Members for the period from ' 
            . $this->period['from']->format('Y-m-d') 
            . ' to ' . $this->period['to']->format('Y-m-d');
        } elseif ($this->view == 'period') {
            $period = $this->selectPeriod === 'yearweek' ? ' Week beginning ' : ' Day ' ;
            return ucwords($this->branch_id) .' Branch Activities by '  . ($period) . '  for the period from ' 
            . $this->period['from']->format('Y-m-d') 
            . ' to ' . $this->period['to']->format('Y-m-d');

        } else {
            return ucwords($this->branch_id) .' Branch Activities by Branch for the period from ' 
            . $this->period['from']->format('Y-m-d') 
            . ' to ' . $this->period['to']->format('Y-m-d');
        }
    }
    /**
     * [_getYearWeekDate description]
     * 
     * @param  Collection $categories [description]
     * @return [type]                 [description]
     */
    private function _getYearWeekDate(Collection $categories) : Collection
    {
        foreach ($categories as $category) { 
            list ( $year,$week) = explode('-', $category);
            $d = new \DateTime;
            $data[] = $d->setISODate($year, $week)->format('Y-m-d');
        }
        return collect($data);
    }
    
}
