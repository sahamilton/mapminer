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
     * @param  [type] $field [description]
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
     * @return [type] [description]
     */
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
    /**
     * [_getInitialBranchId description]
     * 
     * @return [type] [description]
     */
    private function _getInitialBranchId()
    {
        
        if (! $this->branch_id) {
            
            $myBranches= $this->person->myBranches();
           
            $this->branch_id = array_key_first($myBranches);

        }
    }
    /**
     * [_getContacts description]
     * 
     * @return [type] [description]
     */
    private function _getContacts() 
    {
        return Contact::query()
            ->select('contacts.id', 'contacts.firstname', 'contacts.lastname', 'contacts.fullname', 'contacts.email', 'contacts.contactphone', 'contacts.title', 'businessname', 'city', 'state', 'zip', 'contacts.address_id')
            ->join('address_branch', 'contacts.address_id', '=', 'address_branch.address_id')
            ->join('addresses', 'address_branch.address_id', '=', 'addresses.id')
            ->whereNotNull('email')
            
            ->where('address_branch.branch_id', $this->branch_id)
            
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
