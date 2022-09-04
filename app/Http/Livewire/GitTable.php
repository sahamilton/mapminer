<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Excel;
use App\Models\GitVersion;
use App\Models\PeriodSelector;
use App\Exports\GitHistoryExport;


class GitTable extends Component
{
    use WithPagination, PeriodSelector;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $sortField = 'commitdate';
    public $sortAsc = true;
    public $search ='';
    public $setPeriod = 'thisQuarter';


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
    public function render()
    {
        $this->_setPeriod();
       
        return view(
            'livewire.git-table',
            [
                'versions'=>GitVersion::query()
                
                    ->periodActions($this->period)
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
                
                
            ]
        );
    }
    /**
     * [_setPeriod description]
     *
     * @return setPeriod
     */
    private function _setPeriod()
    {
        if ($this->setPeriod != session('period')) {
            $this->livewirePeriod($this->setPeriod);
            
        }
    }

    public function export()
    {
               
        return Excel::download(
            new GitHistoryExport(
                $this->period
            ), 'githistory.csv'
        );
    }

}
