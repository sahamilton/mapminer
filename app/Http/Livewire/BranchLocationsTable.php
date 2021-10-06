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
    public $sortField = 'distance';
    public $sortAsc = true;
    public $search = '';
    public $branch;
    public $range;
    public $distance;
    public $accounttype=false;
    public $paginationTheme = 'bootstrap';
  
    

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingDistance()
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
    public function mount(int $branch)
    {
        
               
        $this->branch = Branch::findOrFail($branch);
        $this->distance = $this->branch->radius;
       
    }



    public function render()
    {

        return view(
            'livewire.branch-locations-table', [
            'addresses'=>
                Address::query()
                    ->search($this->search)
                    ->nearby($this->branch, $this->distance)
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