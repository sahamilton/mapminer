<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Address;
use App\Person;
use App\Company;
use App\Branch;


class CompanyLocationTable extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'distance';
    public $state='all';
    public Company $company;
    public $company_id;
    public $sortAsc = true;
    public $search ='';
    //public Branch $branch;
    public Person $person;
    public $claimed='All';
    public $myBranch = false;
    public $myBranches;
    public $distance = '25'
    ;


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
    /**
     * [mount description]
     * 
     * @param [type] $company_id [description]
     * 
     * @return [type]             [description]
     */
    public function mount(Company $company, $distance=null)
    {

       
        $this->company_id = $company->id;
        $this->company = $company->load('salesnotes');
        $this->person = Person::where('user_id', auth()->user()->id)->first();
        $this->myBranches = auth()->user()->person->getMyBranches();
        
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        
        return view(
            'livewire.company-location-table', [
                'locations'=>Address::query()

                    ->distanceTo($this->person)
                    ->where('company_id', $this->company_id)
                    ->when(
                        $this->state != 'all', function ($q) {
                                $q->where('state', $this->state);
                        }
                    )
                    ->when(
                        $this->claimed != 'All', function ($q) {
                            $q->when(
                                $this->claimed == 'claimed', function ($q) {
                                    $q->has('assignedToBranch');
                                }, function ($q) {
                                    $q->doesntHave('assignedToBranch');
                               
                                }
                            );
                        }
                    )->when(
                        $this->myBranch, function ($q) {
                            $q->whereHas(
                                'assignedToBranch', function ($q) {
                                    $q->whereIn('branches.id', $this->myBranches);
                                }
                            );
                        }
                    )
                    ->when(
                        $this->distance != 'any', function ($q) {
                            $q->nearby($this->person, $this->distance);
                        }
                    )
                    ->search($this->search)
                    ->with('assignedToBranch')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
            'allstates' => $this->_allStates(),
            
            'distances' => ['any'=>'Any',5=>5,10=>10, 25=>25],
            'status'=>[" All"=>"All", "claimed"=>"Claimed","unclaimed"=>'Unclaimed'],
            'owned' =>['true'=>'My Branch', 'false'=>'All'],
            ]
        );
        
    }

    private function myPosition()
    {
        $this->person->getMyPosition();
    }

    private function _allStates()
    {
        $states = Address::select('state')
                ->distinct('state')
                ->select('state')
                ->where('company_id', $this->company_id)
                ->orderBy('state')
                ->pluck('state','state')->toArray();
        $states['all']=' All';
        asort($states);
        return $states;
    }
}
