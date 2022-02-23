<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Address;
use App\Contact;
use App\Activity;
use App\Opportunity;
use App\ActivityType;

class AddressCard extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search ='';
    public $setPeriod = 'thisWeek';
    public $activitytype = "All";
    public $model = 'All';
    public $paginationTheme = 'bootstrap';
    public $view = 'summary';
    public $owned = false;
    public Address $address;
    public array $myBranches;
    public array $activityTypes;


    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingView()
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
    public function mount(Address $address)
    {
        $this->address = $address->load(
            'company', 
            'leadsource', 
            'assignedToBranch')
            ->loadCount(
                'contacts', 
                'activities', 
                'opportunities');
        $this->myBranches = $this->_getMyBranches();
        $this->owned = $this->_checkIfOwned();
        $this->activityTypes = ActivityType::pluck('activity', 'id')->toArray();
    }
    public function render()
    {
        return view('livewire.address-card',
            [

                
                'viewdata'=> $this->_getViewData(),
                'statuses' =>[1=>'Offered to',2=>'Owned by','4'=>'Owned by*'],
                'viewtypes'=>[
                    'summary'=>'Summary',
                    'contacts'=>'Contacts', 
                    'activities'=>'Activities', 
                    'opportunities'=>'Opportunities'],

            ]
        );
    }
    private function _getViewData()
    {
        switch($this->view) {

            case 'contacts':
                return Contact::where('address_id', $this->address->id)
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);
                break;
            case 'activities':
                return Activity::where('address_id', $this->address->id)
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);
                break;
            case 'opportunities':
                return Opportunity::where('address_id', $this->address->id)
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);
                break;
        }
    }
    public function changeview($view)
    {
        $this->view = $view;
    }
    private function _checkIfOwned()
    {
        
        $assignedTo = $this->address->assignedToBranch
            ->where('pivot.status_id', 2)
            ->pluck('id')
            ->toArray();
        return array_intersect($assignedTo, $this->myBranches);

    }

    private function _getMyBranches()
    {
        return auth()->user()->person
            ->branchesManaged()
            ->pluck('id')
            ->toArray();
    }
}
