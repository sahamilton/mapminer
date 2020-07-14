<?php

namespace App\Http\Livewire;
use App\Opportunity;
use App\Branch;
use App\Person;
use Livewire\Component;
use Livewire\WithPagination;

class OpportunityTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'opportunities.created_at';
    public $sortAsc = true;
    public $search = false;
    public $branch_id;
    public $period;
    public $filter = 0;
    public $myBranches;

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
       
        $this->branch_id = $branch->id;
        $this->period = session('period');
        $person = new Person();
        $this->myBranches = $person->myBranches();
    }
    public function render()
    {
    
        return view('livewire.opportunity-table', 
            [
                'opportunities' => Opportunity::query()
                    ->select('opportunities.*','businessname')
                    ->where('branch_id', $this->branch->id)
                    ->where('closed', $this->filter)
                    ->join('addresses', 'addresses.id', '=', 'opportunities.address_id')
                    ->withLastactivity()
                    ->when(
                        $this->search, function ($q) {
                            $q->search($this->search);
                        }
                    )
                    ->distinct()
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'branch'=>Branch::query()->findOrFail($this->branch_id),
            ]
        );
    }
}