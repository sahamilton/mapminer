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
        )->has('locations')->get();
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
                    ->with('assignedToBranch', 'currentcampaigns')
                    ->search($this->search)
                    ->withLastActivityId()
                    ->when(
                        $this->state_code != 'All', function ($q) {
                            $q->whereState($this->state_code);
                        }
                    )
                    ->with('lastActivity')
                    ->dateAdded()
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'states' => Address::where('company_id', $this->company_id)
                    ->distinct('state')->orderBy('state')->pluck('state'),
                'company'=>Company::findOrFail($this->company_id),

                    ]
        );
    }
}
