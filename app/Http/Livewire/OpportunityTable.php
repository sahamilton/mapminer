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
    public $sortField = 'opportunities.created_at';
    public $sortAsc = true;
    public $search ='';
    public $branch;
    public $period;
    public $filter = 0;
    public $myBranches;

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
    public function mount($branch)
    {
        $this->branch = Branch::findOrFail($branch->id);
        $this->period = session('period');
    }
    public function render()
    {
        
        return view('livewire.opportunity-table', [
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
            ]
        );
    }
}

/*
return view('livewire.invoice-table', [
        
                'invoices' => Invoice::query()->select('invoices.*')
                ->join('clients', 'clients.id', '=', 'invoices.client_id')
                ->when(
                    $this->client, function ($q) {
                        $q->where('client_id', $this->client);
                    }
                )
                ->summary()
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),

                ]
 */
