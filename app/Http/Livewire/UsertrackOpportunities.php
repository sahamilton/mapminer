<?php

namespace App\Http\Livewire;
use App\Opportunity;
use App\Branch;
use App\Person;
use Livewire\Component;
use Livewire\WithPagination;

class UsertrackOpportunities extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'opportunities.created_at';
    public $sortAsc = true;
    public $search = '';
    public $user;
    public $period;
    public $setPeriod;
    public $filter ='all';
   


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
    public function mount($user, $period)
    {
        $this->user = $user;
        $this->period = $period;
        
    }
    public function render()
    {
        $this->_setPeriod();
        return view(
            'livewire..usertrack-opportunities', 
            [
                'opportunities' => Opportunity::query()
                    ->userActions($this->user)
                    ->periodActions($this->period)
                    ->when(
                        $this->filter != 'all', function ($q) {
                            $q->where('closed', $this->filter);
                        }
                    )->withLastactivity()
                    ->when(
                        $this->search, function ($q) {
                            $q->search($this->search);
                        }
                    )
                    ->distinct()
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage)
                
            ]
        );
    }

    private function _setPeriod()
    {
        
        $branch = Branch::first();
        $this->period = $branch->getPeriod($this->setPeriod);
        
       


    }
}
