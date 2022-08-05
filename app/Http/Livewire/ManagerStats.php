<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;
use App\Activity;
use App\AddressBranch;
use App\Branch;
use App\Opportunity;
use App\Person;
use App\Role;

class ManagerStats extends Component
{
    use PeriodSelector, WithPagination;
    public $role_id = 3;
    public $perPage='10';
    public $search = '';
    public $sortField = 'lastname';
    public $salesroles = [3,6,7,9,14,17];
    public $sortAsc = true;
    public $manager_id;
    public $person;
    public $fields = ['activitystats'=>'Avg Activities', 'leadstats'=>'Avg New Leads', 'opportunitystats'=>'Avg New Opportunities',  'opportunitywincount'=>'Avg Won Opportunities','opportunitywinvalue'=>'Avg Won Value'];
    public $setPeriod ='lastMonth';
    public $paginationTheme ='bootstrap';


    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

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

    public function setManager($person)
    {
        
        $this->manager_id = $person;

    }

    public function updatingRoleId()
    {
        $this->manager_id = null;
    }
    public function mount($manager=null)
    {
        if ($manager) {
            $this->manager_id =$manager;
        } else {
            $this->manager_id = config('mapminer.topdog');
        }
        
    }


    public function render()
    {
        
        $this->_setPeriod();
        return view(
            'livewire.manager-stats',
            [
                'people'=>$this->_getmanagerStats(),
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
                'roles'=> Role::type('sales')->orderBy('display_name')->pluck('display_name', 'id'),
            ]
        );
    }


    private function _getmanagerStats()
    {
        
        $this->days = $this->period['from']->diff($this->period['to'])->days;
        if ($this->manager_id) {
            $this->person = Person::with(
                [
                    'directReports'=>function ($q) {
                        $q->search($this->search)
                            ->wherehas(
                                'userdetails.roles', function ($q) {
                                    $q->whereIn('role_id', $this->salesroles);
                                }
                            );
                    }
                ]
            )
            
            ->findOrFail($this->manager_id);
            $people = $this->person->directReports;
            
             
            
        } else {
            $people = Person::wherehas(
                'userdetails.roles', function ($q) {
                    $q->where('role_id', $this->role_id);
                }
            )->with('directReports', 'reportsTo', 'userdetails.roles')
            ->search($this->search)
            ->get();

        }


    
        foreach ($people as $person) {

            $branches = $person->getMyBranches();
            if (count($branches)>0) {
                
            
                foreach ($this->fields as $field=>$label) {

                    switch($field) {
                    case 'activitystats':
                         $activities = $this->_getActivityStats($branches);

                            $person->activitystats = $activities / (count($branches)*$this->days);

                        break;
                    case 'leadstats':

                        $leads = $this->_getLeadStats($branches);
                        $person->leadstats = $leads / (count($branches)*$this->days);
                        break;
                    
                    
                    case 'opportunitystats':
                    case 'opportunitywinvalue':
                    case 'opportunitywincount':
                        $wins = Branch::whereIn('id', $branches)->summaryOpportunities($this->period, ['new_opportunities','won_value', 'won_opportunities'])->get();
                        $person->opportunitystats = $wins->sum('new_opportunities') / (count($branches)*$this->days);    
                        $person->opportunitywinvalue = $wins->sum('won_value') / (count($branches)*$this->days);
                        $person->opportunitywincount = $wins->sum('won_opportunities') / (count($branches)*$this->days);

                        break;


                    }
                }

            }

        }
   
        if (! $this->sortAsc) {
            return $people->sortByDesc($this->sortField)->paginate($this->perPage);
        } else {
            return $people->sortBy($this->sortField)->paginate($this->perPage);
        }
            

    }

    private function _getActivityStats(Array $branches)
    {
        return Activity::completed()
            ->whereBetween('activity_date', [$this->period['from'], $this->period['to']])
            ->whereIn('branch_id', $branches)
            ->count();
    }

    private function _getLeadStats(Array $branches)
    {
        return AddressBranch::whereBetween('created_at', [$this->period['from'], $this->period['to']])
            ->whereIn('branch_id', $branches)
            ->count();
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
}
