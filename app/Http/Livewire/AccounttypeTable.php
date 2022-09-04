<?php

namespace App\Http\Livewire;
use App\Models\AccountType;
use App\Models\Company;
use App\Models\Person;
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
     * @param str $field [description]
     * 
     * @return null
     */
    public function sortBy(str $field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    /**
     * [rules description]
     * 
     * @return [type] [description]
     */
    public function rules() 
    {
        return [

            'activity.type'=>'required',

        ];
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
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
                
                'accounttypes'=>AccountType::orderBy('type')
                    ->pluck('type', 'id')
                    ->prepend('All', 'All')
                    ->toArray(),
                
                'managers' => Person::has('managesAccount')
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->get()
                    ->pluck('complete_name', 'id')
                    ->prepend('All', 'All')
                    ->toArray(),            
            ]
        );
    }
    /**
     * [addAccounttype description 
     *
     * @return null
     */
    public function addAccounttype()
    {
        $this->__createBlankAccountType();

        $this->activityTypeModal = true;
    }
    /**
     * [addActivityType description]
     *
     * @return null
     */
    public function addActivityType()
    {
        $this->validate();
        $this->activityType->save();
        $this->activityTypeModal = false;

    }
    /**
     * [_createBlankAccountType description]
     * 
     * @return [type] [description]
     */
    private function _createBlankAccountType()
    {
        $this->activityType = ActivityType::make();
    }
}
