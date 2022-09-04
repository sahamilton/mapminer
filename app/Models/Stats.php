<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    use HasFactory;
    public $period;
    public $priorPeriod;
    public $manager;
    public $branches;
    public $team;
    
    public function __construct(Array $period, Person $manager=null)
    {
        $this->period = $period;
        $this->_getPriorPeriod();
        $this->manager = $manager;

        if ($this->manager) {
            $this->branches = $this->_getManagerBranches();
            $this->team = $this->_getManagerTeam();
        }

    }

    /**
     * [getUsageStats description]
     * 
     * @return [type] [description]
     */
    public function getUsageStats() : array
    {
               
        return [
            'current'=> $this->metrics($this->period),
            
            'prior'=> $this->metrics($this->priorPeriod),
            
            'period'=>[
                'current'=>$this->period,
                'prior'=>$this->priorPeriod,
            ]
        ];

        
    }
    /**
     * _getPriorPeriod  Calculate appropriate prior perdiod
     * 
     * @return [type] [description]
     */
    private function _getPriorPeriod()
    {

        $days = $this->period['from']->diffInDays($this->period['to'])+1;
        $this->priorPeriod['from'] = $this->period['from']->copy()->subDays($days);
        $this->priorPeriod['to'] = $this->period['from']->copy()->subDay();
        
       
    }

    private function _getManagerTeam()
    {
        return $this->manager
            ->team()
            ->pluck('id', 'user_id')
            ->toArray();
    }

    private function _getManagerBranches()
    {
        return $this->manager->getMyBranches();
    }


    private function metrics(array $period) : array
    {
        
        return [
                'logins' => Track::whereBetween('lastactivity', [$period['from'], $period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )
                    ->count(),
                'registered_users'=>User::where('created_at', '<=', $period['to'])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )->count(),
                'active_users' => Track::whereBetween('lastactivity', [$period['from'], $period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )
                    ->distinct('user_id')->count(),
                'new_users' => User::withTrashed()->whereBetween('created_at', [$period['from'], $period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )->count(),
                'deleted_users' => User::withTrashed()->whereBetween('deleted_at', [$period['from'], $period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )
                    ->count(),
                'active_branches' => Branch::when(
                    $this->branches, function ($q) {
                        $q->whereIn('id', $this->branches);
                    }
                )->wherehas(
                    'activities', function ($q) use ($period) {
                        $q->whereBetween('activity_date',  [$period['from'], $period['to']]);
                    }
                )->count(),
                'inactive_branches' => Branch::when(
                    $this->branches, function ($q) {
                        $q->whereIn('id', $this->branches);
                    }
                )->whereDoesntHave(
                    'activities', function ($q) use ($period) {
                        $q->whereBetween('activity_date',  [$period['from'], $period['to']]);
                    }
                )->count(),
                'branches_without_manager'=>Branch::doesntHave('manager')->count(),
                'activities' => Activity::completed()->whereBetween('activity_date', [$period['from'], $period['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->count(),
                'new_leads' => Address::whereBetween('created_at', [$period['from'], $period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )->count(),
                
                'new_opportunities' => Opportunity::whereBetween('created_at', [$period['from'], $period['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->count(),
                
                'new_opportunities_value' => Opportunity::whereBetween('created_at', [$period['from'], $period['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->sum('value'),
                
                'won_opportunities' => Opportunity::whereBetween('actual_close', [$period['from'], $period['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )
                    ->where('closed', 1)
                    ->count(),
                
                'won_value' => Opportunity::whereBetween('actual_close', [$period['from'], $period['to']])
                    ->where('closed', 1)
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )
                ->sum('value'),
            ];
    }

}
