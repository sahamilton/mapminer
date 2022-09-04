<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Person;
use Livewire\WithPagination;
class ChangeReporting extends Component
{
    use WithPagination;
   
    public $perPage = 10;
    public $sortField = 'lastname';
    public $sortAsc = true;
    public $search = '';
    public $paginationTheme = 'bootstrap';
    public Person $person;
    public $possibles;
    public $allChangeTo = null;
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

    public function mount(Person $person)
    {
        $this->person = $person;

        $this->possibles = $person->reportsTo->directReports->where('id', '!=', $person->id);
            
    }

    public function render()
    {
        return view(
            'livewire.users.change-reporting', [

            'reports' => Person::where('reports_to', $this->person->id)

                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage),


            ]
        );
    }

    public function allChange()
    {
        
        Person::where('reports_to', $this->person->id)
            ->update(['reports_to'=> $this->allChangeTo]);
    }
}
