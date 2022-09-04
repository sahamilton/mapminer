<?php

namespace App\Http\Livewire;
use App\Models\Address;
use App\Models\Activity;
use App\Models\Company;
use App\Models\Person;
use App\Models\PeriodSelector;
use App\Models\ActivityType;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AddressBranch;

class Namdetail extends Component
{
    use WithPagination, PeriodSelector;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = null;
   
    public $withOps = 'All';
    //public $updateMode = false;
    public $setPeriod = 'All';

    public $activitytype_id;
    public $note;
    public $activity_date='2021-02-03';
    public $completed =1;
    public $followup_date;
    public $followup_activity;
    public $address_id;
    public $lead_source_id = 'All';
    public Person $manager;
    public Company $company;
   
   

 
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingLeadSourceId()
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
     * @return [type]         [description]
     */
    public function mount($branch_id=null)
    {
       
        $this->manager = Person::with('managesAccount')
            ->findOrFail(auth()->user()->person->id);
      
        
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
        
        return view(
            'livewire.lead-table', [
            'leads' => Address::query()
                ->where('company_id', $this->company_id)
                ->search($this->search)
                ->when(
                    $this->withOps != 'All', function ($q) {
                        $q->when(
                            $this->withOps == 'Without', function ($q) {
                                $q->whereDoesntHave('opportunities');
                            }
                        )
                        ->when(
                            $this->withOps == 'Only Open', function ($q) {
                                $q->whereHas(
                                    'opportunities', function ($q) {
                                        $q->where('closed', 0);
                                    }
                                );
                                
                            }
                        )
                        ->when(
                            $this->withOps == 'Any', function ($q) {
                                $q->has('opportunities');
                            }
                        );
                        
                    }
                )
                ->whereHas(
                    [
                        'assignedToBranch'=>function ($q) {
                            $q->where('address_branch.branch_id', $this->branch_id);
                        }
                    ]
                )
                ->with('assignedToBranch')

                ->withLastActivityId()
                ->with('lastActivity')
                ->dateAdded()
                ->orderByColumn($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'opstatus'=>['All', 'Without', 'Only Open', 'Any'],
                'activities'=>ActivityType::pluck('activity', 'id')->toArray(),
                'companies'=>Company::where('person_id', $this->manager->id)
                    ->pluck('companyname', 'id')->toArray(),
            ]
        );
    }

    
    private function _setPeriod()
    {
        //dd(Person::where('user_id', auth()->user()->id)->first());
        if ($this->setPeriod != 'All') {
            $this->period = Person::where('user_id', auth()->user()->id)->first()->getPeriod($this->setPeriod);
        }
        
    }
}
