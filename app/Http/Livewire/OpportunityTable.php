<?php

namespace App\Http\Livewire;
use App\Opportunity;
use App\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class OpportunityTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search = '';
    public $branch;
    public $period;
    public $filter = 0;
 

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
        $this->period = session('period');
    }
    public function render()
    {
  
        
        return view('livewire.opportunity-table', [
            'opportunities' => Opportunity::query()
                ->search($this->search)
                ->where('branch_id', $this->branch->id)
                ->where('closed', $this->filter)
                ->with(
                    [
                        'address.address'=>function ($query) {
                            $query->withLastActivityId();
                        },'address.address.lastActivity'
                    ]
                )
                ->thisPeriod($this->period)
                ->when(
                    $this->search, function ($q) {
                        $q->search($this->search);
                    }
                )
                ->distinct()
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]
        );
    }
}