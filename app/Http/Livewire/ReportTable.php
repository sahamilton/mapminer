<?php

namespace App\Http\Livewire;
use App\Report;
use Livewire\Component;
use Livewire\WithPagination;
class ReportTable extends Component
{
    
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'report';
    
    public $sortAsc = false;
    public $search ='';
    public $type;
    public array $types;
    public $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function mount()
    {
        if (auth()->user()->hasRole(['admin'])) {
            $this->type=='all';
            $this->types =['all'=>'All', 'public'=>'Public'];
        } else {
            $this->type= 'public';
            $this->types =['public'=>'Public'];
            
        }
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
        return view('livewire.report-table',
            ['reports'=>Report::query()
            ->when(
                $this->type != 'all', function ($q) {
                    $q->when(
                        $this->type=='public', function ($q) {
                            $q->where('public', 1);
                        }
                    );
                }
            )
            ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),
            ]
        );
    }
}
