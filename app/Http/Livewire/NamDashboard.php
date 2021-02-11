<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Company;
use App\Address;
use App\Person;

class NamDashboard extends Component
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
    public $status = 'Unassigned';
    public $withOps = 'All';


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
        $this->person = Person::findOrFail(auth()->user()->person->id);
        $companies = Company::whereHas(
            'managedBy', function ($q) {
                $q->where('id', $this->person->id);
            }
        )
        ->has('locations')->get();
        $this->companies = $companies->pluck('companyname', 'id')->toArray();
        $this->company_id = array_keys($this->companies)[0];
        
    }
    
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        ray($this->company_id);
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
}
