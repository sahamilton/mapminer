<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Oracle;
class UserCreate extends Component
{
    
    public $userSelected;
    public Oracle $user;
    protected $listeners = ['userSelected'];

    public function userSelected(Oracle $user)
    {
        $this->$user = $user;
    }


    public function render()
    {
        return view('livewire.user-create');
    }
}
