<?php

namespace App;

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

    
    public function getUsageStats()
    {
        
       
        return [
            'current'=>
            [
                'logins' => Track::whereBetween('lastactivity', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )
                    ->count(),
                'registered_users'=>User::where('created_at', '<=', $this->period['to'])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )->count(),
                'active_users' => Track::whereBetween('lastactivity', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )
                    ->distinct('user_id')->count(),
                'new_users' => User::withTrashed()->whereBetween('created_at', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )->count(),
                'deleted_users' => User::withTrashed()->whereBetween('deleted_at', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )
                    ->count(),
                'active_branches' => Branch::wherehas(
                    'activities', function ($q) {
                        $q->whereBetween('activity_date',  [$this->period['from'], $this->period['to']]);
                    }
                )
                ->count(),
                'inactive_branches' => Branch::whereDoesntHave(
                    'activities', function ($q) {
                        $q->whereBetween('activity_date',  [$this->period['from'], $this->period['to']]);
                    }
                )
                ->count(),
                'activities' => Activity::completed()->whereBetween('activity_date', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->count(),
                'new_leads' => Address::whereBetween('created_at', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )->count(),
                
                'new_opportunities' => Opportunity::whereBetween('created_at', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->count(),
                
                'new_opportunities_value' => Opportunity::whereBetween('created_at', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->sum('value'),
                
                'won_opportunities' => Opportunity::whereBetween('actual_close', [$this->period['from'], $this->period['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )
                    ->where('closed', 1)
                    ->count(),
                
                'won_value' => Opportunity::whereBetween('actual_close', [$this->period['from'], $this->period['to']])
                    ->where('closed', 1)
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )
                    ->sum('value'),
            ],
            'prior'=>
            [

                'logins' => Track::whereBetween('lastactivity', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )
                    ->count(),
                'registered_users'=>User::where('created_at', '<=', $this->priorPeriod['to'])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )->count(),
                'active_users' => Track::whereBetween('lastactivity', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )
                    ->distinct('user_id')->count(),
                'new_users' => User::withTrashed()->whereBetween('created_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )->count(),
                'deleted_users' => User::withTrashed()->whereBetween('deleted_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('id', array_keys($this->team));
                        }
                    )
                    ->count(),
                'activities' => Activity::completed()->whereBetween('activity_date', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->count(),
                'new_leads' => Address::whereBetween('created_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->team, function ($q) {
                            $q->whereIn('user_id', array_keys($this->team));
                        }
                    )->count(),
                
                'new_opportunities' => Opportunity::whereBetween('created_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->count(),
                
                'new_opportunities_value' => Opportunity::whereBetween('created_at', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )->sum('value'),
                
                'won_opportunities' => Opportunity::whereBetween('actual_close', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )
                    ->where('closed', 1)
                    ->count(),
                
                'won_value' => Opportunity::whereBetween('actual_close', [$this->priorPeriod['from'], $this->priorPeriod['to']])
                    ->where('closed', 1)
                    ->when(
                        $this->branches, function ($q) {
                            $q->whereIn('branch_id', $this->branches);
                        }
                    )
                    ->sum('value'),


            ],
            'period'=>[
                'current'=>$this->period,
                'prior'=>$this->priorPeriod,
            ]
        ];

        
    }
    private function _getPriorPeriod()
    {

        $days = $this->period['from']->diffInDays($this->period['to']);

        if ($days <= 7) {
            $this->priorPeriod['from'] = $this->period['from']->copy()->subWeek(); 
        } else {
            
            $this->priorPeriod['from'] = $this->period['from']->copy()->subMonth(intdiv($days, 28));
        }
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


}
