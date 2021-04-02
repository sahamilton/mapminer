<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;
use App\Branch;
use App\Company;
use App\Person;

class Namdashboard extends Component
{
    
    use WithPagination, PeriodSelector;
    public $perPage=10;
    public $sortField='id';
    public $sortAsc=true;
    public $search ='';
    public $myBranches;
    public $status = 'withOpportunities';
    
    public $setPeriod;
   
    public Company $company;
    public $company_id;
    public Person $manager;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingCompany_id()
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
    public function mount($manager_id=null)
    {
        if (! $manager_id) {
            $this->manager = Person::with('managesAccount')
                ->findOrFail(auth()->user()->person->id);
        } else {
            $this->manager = Person::with('managesAccount')
                ->findOrFail($manager_id);
        }
        
        $this->period = $this->getPeriod();
        $this->company = $this->manager->managesAccount->first();
        $this->company_id = $this->manager->managesAccount->first()->id;
        $this->setPeriod = $this->period['period'];
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->_setPeriod();
        $this->_getCompanySummary();
        return view(
            'livewire.namdashboard', 
            [
                'branches'=>Branch::select('id', 'branchname')
                    ->when(
                        $this->status == 'withOpportunities', function ($q) {
                            $q->whereHas(
                                'opportunities', function ($q) {
                                    $q->where('opportunities.created_at', '<', $this->period['to'])
                                        ->where(
                                            function ($q) {
                                                $q->where('closed', 0)
                                                    ->orWhere(
                                                        function ($q) {
                                                            $q->where('actual_close', '>', $this->period['to'])
                                                                ->orWhereNull('actual_close');
                                                        }
                                                    );
                                            }
                                        )
                                        ->whereHas(
                                            'location', function ($q) {
                                                $q->where('company_id', $this->company_id);
                                            }
                                        );
                                }
                            );
                        }
                    )
                    ->summaryNAMStats($this->period, [$this->company_id])
                    ->with('manager', 'manager.reportsTo')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'companies'=>Company::where('person_id', $this->manager->id)
                    ->pluck('companyname', 'id')->toArray(),
            ]
        ); 

    }

    private function _setPeriod()
    {
        if ($this->setPeriod != session('period')) {
            $this->livewirePeriod($this->setPeriod);
            
        }
    }

    private function _getCompanySummary()
    {
        $this->company = Company::companyDetail($this->period)->findOrFail($this->company_id);
    }
}
