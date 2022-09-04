<?php

namespace App\Http\Livewire;
use App\Models\Address;

use Livewire\Component;
use Livewire\WithPagination;
class AddressDuplicates extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = false;
    public $search ='';
    public array $owned;
    public Address $address;
    public $branch_id;
    public $address_branch_id;
    public $myBranches;


    /**
     * [updatingSearch description]
     * 
     * @return [type] [description]
     */
    public function updatingSearch() :void
    {
        $this->resetPage();
    }
    /**
     * [updatingView description]
     * 
     * @return [type] [description]
     */
    public function updatingView() :void
    {
        $this->resetPage();
    }
    /**
     * [sortBy description]
     * 
     * @param [type] $field [description]
     * 
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
     * @param Address    $address [description]
     * @param array|null $owned   [description]
     * 
     * @return [type]              [description]
     */
    public function mount(Address $address, array $owned=null)
    {
        $this->address = $address->load('duplicates');
        $this->owned = $owned;
        if ($branch = Address::with('claimedByBranch')->findOrFail($address->id)->claimedByBranch->first()) {
            $this->branch_id = $branch->id; 
            $this->address_branch_id = $branch->pivot->id;
            
        }
        $this->myBranches = $this->_getMyBranches();
    }
    public function render()
    {
        return view('livewire.address-duplicates');
    }

    /**
     * [_getMyBranches description]
     * 
     * @return [type] [description]
     */
    private function _getMyBranches()
    {
        return auth()->user()->person
            ->getMyBranches();
    }
}
