<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;
use App\Branch;

class ManagerSummary extends Component
{
    
    use WithPagination, PeriodSelector;
    public $paginationTheme = 'bootstrap';
    public $perPage=10;
    public $sortField='branchname';
    public $sortAsc=true;
    public $search ='';
    public $myBranches;
    public $leadFields = [
            'active_leads'
        ];
    public $opportunityFields =[
            "lost_opportunities",
            "open_opportunities",
            "won_opportunities",
        ];
    public $activityFields = [];
    public $setPeriod;

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
    public function mount()
    {
        $this->myBranches = auth()->user()->person->getMyBranches();
        $this->period = $this->getPeriod();
        if (! session()->has('period')) {
            $this-> _setPeriod();
        } 
        $this->setPeriod = session('period')['period'];
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
            'livewire.manager-summary', 
            ['branches'=>Branch::select('id', 'branchname')
                ->SummaryLeadStats($this->period, $this->leadFields)
                ->SummaryOpportunities($this->period, $this->opportunityFields)
                ->SummaryActivities($this->period, $this->activityFields)
                ->with('manager', 'manager.reportsTo')
                ->whereIn('id', $this->myBranches)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]
        ); 

    }

    private function _setPeriod()
    {
        
            $this->livewirePeriod($this->setPeriod);

    }
}
