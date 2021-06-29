<?php

namespace App\Http\Livewire;

use Livewire\Component;
use \OwenIt\Auditing\Models\Audit;
use Livewire\WithPagination;
use App\PeriodSelector;

class AuditTable extends Component
{
    use WithPagination, PeriodSelector;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search ='';
    public $setPeriod = 'thisWeek';
    public $activitytype = "All";
    public $model = 'All';
    public $paginationTheme = 'bootstrap';


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
            'livewire.audit-table',
            [
                'audits'=>Audit::with('user.person')
                    ->when(
                        $this->model != 'All', function ($q) {
                            $q->where('auditable_type', $this->model);
                        }
                    )
                    ->when(
                        $this->activitytype != 'All', function ($q) {
                            $q->where('event', $this->activitytype);
                        }
                    )
                    ->whereBetween('created_at', [$this->period['from'], $this->period['to']])
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
}
