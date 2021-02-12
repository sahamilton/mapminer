<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;
use App\Branch;
use App\Person;
use App\Company;

class namSummary extends Component
{
    
    use WithPagination, PeriodSelector;
    public $perPage=10;
    public $sortField='branchname';
    public $sortAsc=true;
    public $search ='';
    public $companies;
    public $company_id;
    public $person;
    public $person_id;
    public $withOps = 'All';
    public $managers;
    public $fields =['worked_leads',
                    'touched_leads',
                    'activityCount',
                    'new_opportunities',
                    'won_opportunities',
                    'opportunities_open',
                    'won_value',
                    'open_value',];
    
    public $setPeriod;
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
    /**
     * [mount description]
     * 
     * @return [type] [description]
     */
    public function mount()
    {
        
        $this->period = $this->getPeriod();
        $this->setPeriod = $this->period['period'];
        if (auth()->user()->hasRole(['admin'])) {
            $this->managers = $this->_getNAMS();
            $this->person = $this->managers->first();
            $this->person_id = $this->person->id;
        } else {
            $this->person = Person::findOrFail(auth()->user()->person->id);
            $this->person_id = $this->person->id;
        }
        
        $this->_setCompany();
       
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
            'livewire.nam-summary', 
            ['branches'=>Branch::whereHas(
                'leads', function ($q) {
                        $q->where('company_id', $this->company_id);
                }
            )
                ->select('id', 'branchname')
                ->SummaryCompanyStats($this->period, [$this->company_id], $this->fields)
                ->with('manager', 'manager.reportsTo')
                
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]
        ); 

    }

    private function _setPeriod()
    {
        if ($this->setPeriod != session('period')) {
            $this->livewirePeriod($this->setPeriod);
            
        }
    }


    private function _getNAMS()
    {
       
        return 
            Person::
                whereHas(
                    'userdetails.roles', function ($q) {
                        $q->whereIn('name', ['national_account_manager']);
                    }
                )
                ->has('managesAccount')
                ->get();
            
    }
    private function _setPerson()
    {
        $this->person = Person::findOrFail($this->person_id);
    }
    private function _setCompany()
    {
        $companies = Company::whereHas(
            'managedBy', function ($q) {
                $q->where('id', $this->person->id);
            }
        )
        ->has('locations')->get();
        $this->companies = $companies->pluck('companyname', 'id')->toArray();
        $this->company_id = array_keys($this->companies)[0];
    }
}
