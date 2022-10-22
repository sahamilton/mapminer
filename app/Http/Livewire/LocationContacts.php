<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AddressBranch;
use App\Models\Branch;
use App\Models\Person;
use App\Models\Contact;

class LocationContacts extends Component
{
    use WithPagination;    
    public $paginationTheme = 'bootstrap';
    public $branch_id;
    public Person $person;
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
        

        $this->person = Person::where('user_id', auth()->user()->id)->first();
       
       
    }

    public function render()
    {
        $this->_getInitialBranchId();
       
       
        return view(
            'livewire.location-contacts', [
                'contacts'=>$this->_getContacts(),
                'myBranches' => $this->person->myBranches(),
                'branch'=>Branch::findOrFail($this->branch_id),
            ]
        );
    }
    
    private function _getInitialBranchId()
    {
        
        if (! $this->branch_id) {
            
            $myBranches= $this->person->myBranches();
           
            $this->branch_id = array_key_first($myBranches);

        }
    }

    private function _getContacts()
    {

        return Contact::query()
            ->whereHas(
                'addressBranch', function ($q) {
                    $q->where('branch_id', $this->branch_id);
                }
            )
            ->with('location')
            ->when(
                $this->filter !='All', function ($q) {
                    $q->whereNotNull($this->filter);
                }
            )
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

               
    }   
}
