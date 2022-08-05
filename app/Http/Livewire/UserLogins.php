<?php

namespace App\Http\Livewire;
use App\User;
use App\Role;
use App\Person;
use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;

use App\Jobs\NotifyManagerOfNoLogins;

class UserLogins extends Component
{
    use WithPagination, PeriodSelector;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'email';
    public $sortAsc = true;
    public $search ='';
    public $have = 'havent';
    public $roletype='all';
    public $setPeriod = 'lastMonth';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    /**
     * [sortBy description]
     * 
     * @param  [type] $field [description]
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
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        
        $this->_setPeriod();
        
        return view(
            'livewire.user-logins',
            [
                'users'=>$this->_getUsers()
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),

                'roles'=>Role::pluck('display_name', 'id')->prepend('All', 'all'),
                'timeperiods'=>[
                        
                       
                        'thisWeek'=>'This Week',
                       
                        'lastWeek'=>'Last Week',
                        
                       
                        'lastMonth'=>'Last Month',
                        
                      
                        'lastQuarter'=>'Last Quarter',
                        'lastSixMonths'=>'Last Six Months',
                        'lastTwelveMonths'=>'Last Year',
                        'never'=>"Never",

                    ],
                'havehavent'=>['have'=>'Have', 'havent'=>"Have Not"],
            ]
        );
    }

    public function emailManagers()
    {
        
        $users = $this->_getusers()->pluck('id')->toArray();


        NotifyManagerOfNoLogins::dispatch($users, $this->period, $this->roletype);


    }
    private function _getusers()
    {
        return User::query()
            ->when(
                $this->setPeriod !='never', function ($q) {
                    $q->withCount(
                        [
                            'usage'=>function ($q) {
                                $q->where('lastactivity', '<=', $this->period['from']);
                            }
                        ]
                    );
                   
                }
            )
            ->when(
                $this->roletype != 'all', function ($q) {
                    $q->whereHas(
                        'roles', function ($q) {
                            $q->whereIn('roles.id', [$this->roletype]);
                        }
                    );
                }
            )
            ->when(
                $this->have == 'havent', function ($q) {
                    $q->when(
                        $this->setPeriod !='never', function ($q) {
                            $q->where('lastlogin', '<=', $this->period['from']);
                               
                            
                        }, function ($q) {
                            $q->whereNull('lastlogin');
                        }
                    );
                }, function ($q) {
                    @ray('hrere');
                    $q->when(
                        $this->setPeriod !='never', function ($q) {
                            $q->whereBetween('lastlogin', [ $this->period['from'], $this->period['to']]);
                               
                            
                        }, function ($q) {
                            $q->whereNotNull('lastlogin');
                        }
                    );
                } 
            )
            ->with('roles', 'person.reportsTo');
    }
    /**
     * [_setPeriod description
     *
     * @return null
     */
    private function _setPeriod()
    {
        
        $this->livewirePeriod($this->setPeriod);
            
        
    }
}
