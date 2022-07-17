<?php

namespace App\Http\Livewire;

use App\AccountType;
use App\ActivityType;
use App\Company;
use App\Person;
use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;

class AccountManagerTable extends Component
{
    use WithPagination, PeriodSelector;
   
    public $perPage = 10;
    public $sortField = 'companyname';
    public $sortAsc = true;
    public $search ='';
    public $paginationTheme = 'bootstrap';
    public $manager_id = 'All';
    public $company_id = 'All';
    public $type_id = 'All';
    public $view ='summary';
    public $setPeriod = 'lastMonth';




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
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingView()
    {
        $this->resetPage();
        $this->sortField='companyname';
    }
    /**
     * [sortBy description]
     * 
     * @param STR $field [description]
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
     * [mount description]
     * 
     * @return [type] [description]
     */
    public function mount()
    {
        
        

    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->_setPeriod();
        @ray($this->period);
        return view(
            'livewire.companies.account-manager-table',
            [
                'results'=>Company::has('locations')
                    ->with('type', 'industryVertical')
                    ->when(
                        $this->type_id != 'All', function ($q) {
                            $q->where('accounttypes_id', $this->type_id);
                        }
                    )
                    ->when(
                        $this->company_id != 'All', function ($q) {
                            $q->whereId($this->company_id);
                        }
                    )
                    ->when(
                        $this->manager_id != 'All', function ($q) {
                            $q->whereHas(
                                'managedBy', function ($q) {
                                    $q->where('persons.id', $this->manager_id);
                                }
                            );
                        }
                    )->when(
                        $this->view ==='summary', function ($q) {
                            $q->withCount('locations')

                                ->withCount(
                                    [
                                        'locations as assigned'=>function ($q) {
                                            $q->when(
                                                $this->period['period'] =='allDates', function ($q) {
                                                    $q->has('assignedToBranch');
                                                }, function ($q) {
                                                    $q->whereHas(
                                                        'assignedToBranch', function ($q) {
                                                            $q->where('address_branch.created_at', '<=', $this->period['to']);
                                                        }
                                                    );
                                                }
                                            );
                                        }
                                    ]
                                )
                                ->activitySummary($this->period)
                                ->opportunitySummary($this->period, $branches=null, $field =['open_opportunities']);

                        }
                    )
                    ->when(
                        $this->view ==='activities', function ($q) {
                            $q->ActivitiesTypeCount($this->period);

                        }
                    )
                    ->when(
                        $this->view ==='opportunities', function ($q) {
                            $q->opportunitySummary($this->period);

                        }
                    )
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),


                'companies'=>Company::query()
                    ->has('managedBy')
                    ->has('locations')
                    ->when(
                        $this->manager_id != 'All', function ($q) {
                            $q->whereHas(
                                'managedBy', function ($q) {
                                    $q->where('id', $this->manager_id);
                                }
                            );
                        }
                    )
                    ->orderBy('companyname')
                    ->pluck('companyname', 'id')
                    ->prepend('All', 'All')
                    ->toArray(),

                'managers'=>Person::has('managesAccount')
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->get()
                    ->pluck('fullName', 'id')
                    ->prepend('All', 'All')
                    ->toArray(),

                'types'=>AccountType::has('companies')
                    ->orderBy('type')
                    ->pluck('type', 'id')
                    ->prepend('All', 'All')
                    ->toArray(),

                'activitytypes'=>ActivityType::orderBy('activity')
                    ->pluck('activity', 'id')
                    ->toArray(),

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
                'views'=>['summary'=>'Summary', 'activities'=>'Activities', 'opportunities'=>'Opportunities'],
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
}
