<?php

namespace App\Http\Livewire;
use App\AccountType;
use App\Company;
use Livewire\WithPagination;
use Livewire\Component;

class AccounttypeTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'id';
    public $sortAsc = true;
    public $search ='';
    public $accounttype = 'All';
    public $manager = 'All';
    public $activityTypeModal = false;
    public ActivityType $activityType;

    
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

    public function rules() {
        return [

            'activity.type'=>'required',

        ];
    }

    public function render()
    {
        return view(
            'livewire.accounttypes.accounttype-table',

            [
                'companies' => Company::when(
                    $this->accounttype != 'All', function ($q) {
                        $q->where('accounttypes_id', $this->accounttype);
                    }
                )
                ->when(
                    $this->manager != 'All', function ($q) {
                        $q->where('person_id', $this->manager);
                    }
                )->companyStats()
                ->with('managedBy', 'type')
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
                'accounttypes'=>AccountType::orderBy('type')->pluck('type', 'id')->toArray(),
                'managers' => Company::has('managedBy')
                    ->with('managedBy')
                    ->get()
                    ->map(
                        function ($company) {
                            return [$company->managedBy->id=> $company->managedBy->fullName()];
                        }
                    )
                    ->unique(),            
            ]
        );
    }

    public function addAccounttype()
    {
        $this->_createBlankAccountType();

        $this->activityTypeModal = true;
    }

    public function addActivityType()
    {
        $this->validate();
        $this->activityType->save();
        $this->activityTypeModal = false;

    }

    private function createBlankAccountType()
    {
        $this->activityType = ActivityType::make();
    }
}
