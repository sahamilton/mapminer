<?php

namespace App\Http\Livewire;

use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignTable extends Component
{
    use WithPagination;
   
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search = '';
    public $status = 'All';
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
        return view(
            'livewire.campaign-table',
            [
                'campaigns' => Campaign::query()
                    ->with('author', 'manager', 'companies', 'vertical')
                    ->when(
                        $this->status != 'All', function ($q) {
                            $q->where('status', $this->status);
                        }
                    )
                    ->withCount('branches')
                    ->search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage),
               
                'calendar' => [],
                'statuses'=> ['planned', 'launched','completed'],   
            ]
        );
    }
}
