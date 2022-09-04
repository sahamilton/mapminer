<?php

namespace App\Http\Livewire;
use App\Models\Person;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\PeriodSelector;

class UsertrackTable extends Component
{
    use WithPagination, PeriodSelector;
    
    public $managers;
   
    public $perPage = 10;
    public $sortField = 'logins_count';
    public $sortAsc = false;
    public $search = '';
    public $paginationTheme = 'bootstrap';
    public $setPeriod = 'thisMonth';


    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
   
    public function mount($managers)
    {
        $this->managers = $managers;
    }

    public function render()
    {
        $this->_setPeriod();
        return view(
            'livewire.users.usertrack-table',
            [
                'data'=>$this->_getCountData()
                    ->whereIn('user_id', $this->managers->pluck('user_id')->toarray())
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'fields'=>[
                    'logins_count'=>'Logins', 
                    'activities_count'=>'Activities', 
                    'newleads' =>'New Leads', 
                    'newopportunities'=>'New Opportunities', 
                    'wonopportunities'=>'Won Opportunities'
                ],
            ]
        );
    }

    private function _getCountData()
    {
        return Person::withCount(
            [
                'logins'=>function ($q) { 
                    $q->whereBetween('track.created_at', [$this->period['from'], $this->period['to']]); 
                },

                'activities'=>function ($q) { 
                    $q->whereBetween('activity_date', [$this->period['from'], $this->period['to']])
                        ->where('completed', 1);  
                },
                
                'leads as newleads'=>function ($q) { 
                    $q->whereBetween('addresses.created_at', [$this->period['from'], $this->period['to']]); 
                }, 
                
                'opportunities as newopportunities'=>function ($q) { 
                    $q->whereBetween('opportunities.created_at', [$this->period['from'], $this->period['to']]); 
                },
                
                'opportunities as wonopportunities'=>function ($q) { 
                    $q->where('opportunities.created_at', '=<', $this->period['to'])
                        ->where('actual_close', '=<', $this->period['to'])
                        ->where('closed', 1); 
                },
                
                
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
        if ($this->setPeriod != session('period')) {
            $this->livewirePeriod($this->setPeriod);
            
        }
    }
}
