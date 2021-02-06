<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\AddressBranch;
use App\Branch;
use App\Person;

class LocationContacts extends Component
{
    use WithPagination;

    

    public $branch_id;
    public $myBranches;
    public $perPage=10;
    public $sortField='lastname';
    public $sortAsc=true;
    public $search ='';
    public $filter = 'All';
   
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
    
    public function mount()
    {
        

        
        $person = new Person();
        $this->myBranches = $person->myBranches();
        $this->branch_id = array_key_first($this->myBranches);
       
    }

    public function render()
    {

              
        return view(
            'livewire.location-contacts', [
                'contacts'=>AddressBranch::query()
                    ->where('address_branch.branch_id', $this->branch_id)
                    ->join('addresses', 'address_branch.address_id', '=', 'addresses.id')
                    ->join('contacts', 'address_branch.address_id', '=', 'contacts.address_id')
                    ->when(
                        $this->filter !='All', function ($q) {
                            $q->whereNotNull($this->filter);
                        }
                    )
                    ->select('addresses.id', 'branch_id', 'businessname', 'city', 'state', 'firstname', 'lastname', 'fullname', 'title', 'contactphone', 'email')
                    ->search($this->search)
                    ->orderByColumn($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'branch'=>Branch::query()->findOrFail($this->branch_id),
            ]
        );
    }
    
    
}
