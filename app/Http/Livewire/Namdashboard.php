<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Company;
use App\Address;
use App\Person;
use App\User;

class Namdashboard extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = null;
    public $companies;
    public $state_code = 'All';
    public $company_id;
    public $person;
    public $person_id;
    public $status = 'Unassigned';
    public $withOps = 'All';
    public $managers;


    public function updatingSearch()
    {
        $this->resetPage();
    }
    /**
     * [sortBy description]
     * 
     * @param [type] $field [description]
     * 
     * @return [type]       [description]
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
       
        if ($this->person_id != $this->person->id) {
            $this->_setPerson();
            $this->_setCompany();
        }
        return view(
            'livewire.dashboards.nam-dashboard',
            [
                'locations' => Address::where('company_id', $this->company_id)
                    
                    ->search($this->search)
                    ->withLastActivityId()
                    ->when(
                        $this->state_code != 'All', function ($q) {
                            $q->whereState($this->state_code);
                        }
                    )
                    ->when(
                        $this->status == 'All', function ($q) {
                            $q->with('assignedToBranch');
                        }
                    )
                    ->when(
                        $this->status == 'Unassigned', function ($q) {
                            $q->doesntHave('assignedToBranch');
                        }
                    )
                    ->when(
                        $this->status == 'Assigned', function ($q) {
                            $q->has('assignedToBranch')
                                ->with('assignedToBranch');
                        }
                    )
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
                    ->with('lastActivity')
                    ->dateAdded()
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'states' => Address::where('company_id', $this->company_id)
                    ->distinct('state')->orderBy('state')->pluck('state'),
                'company'=>Company::findOrFail($this->company_id),
                'opstatus'=>['All', 'Without', 'Only Open', 'Any'],

                    ]
        );
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
