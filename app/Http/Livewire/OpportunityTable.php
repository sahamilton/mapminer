<?php

namespace App\Http\Livewire;
use App\Opportunity;
use App\Branch;
use App\Person;
use Livewire\Component;
use Livewire\WithPagination;
use App\PeriodSelector;

class OpportunityTable extends Component
{
    use WithPagination, PeriodSelector;
    public $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortField = 'opportunities.created_at';
    public $sortAsc = true;
    public $search = '';
    public $setPeriod;
    public $branch_id;
    public $filter = '0';
    public $myBranches;


    public function updatingSearch()
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
     * @return [type] [description]
     */
    public function mount($branch_id=null)
    {
        
        
        //$this->period = session('period');
        $person = new Person();
        $this->myBranches = $person->myBranches();
        if (! $branch_id) {
            $this->branch_id = array_key_first($this->myBranches);
        } else {
            $this->branch_id =$branch_id;
        }
        if (! session()->has('period')) {
            $this-> _setPeriod();
        } 
        $this->setPeriod = session('period')['period'];
    }
    /**
     * [render description]
     * 
     * @return [type] [description]
     */
    public function render()
    {
        $this->_setPeriod();
        return view(
            'livewire.opportunity-table', 
            [
                'opportunities' => Opportunity::query()
                    ->select('opportunities.*', 'businessname')
                    ->join('addresses', 'addresses.id', '=', 'opportunities.address_id')
                    ->withLastactivity()
                    ->when(
                        $this->search, function ($q) {
                            $q->search($this->search);
                        }
                    )
                    ->when(
                        $this->setPeriod !='All', function ($q) {
                            $q->whereBetween('opportunities.created_at', [$this->period['from'], $this->period['to']]);
                        }
                    ) 
                    ->when(
                        $this->filter != 'All', function ($q) {
                            $q->where('closed', $this->filter);
                        }
                    )
                    ->where('branch_id', $this->branch_id)
                    ->distinct()
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                'branch'=>Branch::query()->findOrFail($this->branch_id),
                'filters' => ['All'=>'All', 0=>'Open', '1'=>'Closed Won', '2'=>'Closed Lost'],

                
            ]
        );
    }

    private function _setPeriod()
    {
        $this->livewirePeriod($this->setPeriod);
        
    }
}
