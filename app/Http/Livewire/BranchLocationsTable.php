<?php

namespace App\Http\Livewire;
use App\Branch;
use App\Address;
use Livewire\Component;
use Livewire\WithPagination;
class BranchLocationsTable extends Component
{
    
    use WithPagination;
    public $perPage = 10;
    public $sortField = 'companyname';
    public $sortAsc = true;
    public $search = '';
    public $branch;
    public $accounttype=false;


 

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    public function mount($branch)
    {
        
        $this->branch = Branch::findOrFail($branch);
    }



    public function render()
    {
        return view('livewire.branch-locations-table', [
            'addresses'=>
            Address::query()
            ->nearby($this->branch, 25)
            ->search($this->search)
            ->with('company')
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage),
            ]

        );
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