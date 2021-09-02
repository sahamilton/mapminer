<?php

namespace App\Http\Livewire;
use App\Branch;
use App\Address;
use App\Person;
use Livewire\Component;
use Livewire\WithPagination;
class BranchLocationsTable extends Component
{
    
    use WithPagination;
    public $perPage = 10;
    public $sortField = 'businessname';
    public $sortAsc = true;
    public $search = '';
    public $branch;
    public $range;
    public $accounttype=false;
    public $paginationTheme = 'bootstrap';
    public $myBranches;
    public $branch_id;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingBranch()
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
    public function mount($branch=null)
    {
        
        $person = new Person();
        $this->myBranches = $person->myBranches();
        if (! $branch) {
            $branch = array_key_first($this->myBranches);
        }
        $this->branch_id = $branch;
       
    }



    public function render()
    {
        $this->_getBranch();
        return view(
            'livewire.branch-locations-table', [
            'addresses'=>
                Address::query()

                    ->search($this->search)
                    ->nearby($this->branch, $this->branch->radius)
                    ->whereDoesntHave('assignedToBranch')
                    ->with('company', 'industryVertical')
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                
            ]
        );
    }

    

    private function _getBranch()
    {
        $this->branch = Branch::findOrFail($this->branch_id);
    }
}
/*
$roles = \App\Role::pluck('display_name', 'id');
        $mywatchlist= [];
        $data['branch'] = $branch->load('manager.reportsTo','manager.userdetails');
    
        $data['title']='National Accounts';
        $servicelines = Serviceline::all();
        $locations  = $this->address->nearby($branch, 25)->with('company')->get();

        $watchlist = User::where('id', '=', auth()->user()->id)
            ->with('watching')->get();
        foreach ($watchlist as $watching) {
            foreach ($watching->watching as $watched) {
                $mywatchlist[]=$watched->id;
            }
        }
 */