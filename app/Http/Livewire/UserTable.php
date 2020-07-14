<?php

namespace App\Http\Livewire;
use App\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortAsc = true;
    public $search = '';
    public $serviceline =false;

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
        return view('livewire.user-table', 
                    [
                        'users' => User::query()
                        ->with('roles', 'usage', 'person', 'serviceline')
                        ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                        ->paginate($this->perPage),
                    ]
               );
    }
}
