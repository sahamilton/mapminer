<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Oracle;
class UserAutocomplete extends Autocomplete
{
    protected $listeners = ['valueSelected'];

    public function valueSelected(Oracle $user)
    {
        $this->emitUp('userSelected', $user);
    }

    public function query() {
        return Oracle::search($this->search)
        ->doesntHave('mapminerUser')
        ->orderBy('last_name');
    }
}