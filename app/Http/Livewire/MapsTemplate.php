<?php

namespace App\Http\Livewire;
use App\Person;
use App\Address;
use Livewire\Component;

class MapsTemplate extends Component
{
    public Person $person;
    public string $address;
    public $radius = 5;
    public $limit = 100;
    public $type= 'Leads';
    public $perPage=10;
    public function mount()
    {
        $this->person = auth()->user()->person;
    }
    public function render()
    {
        return view(
            'livewire.maps-template',
            [
                'data'=>$this->_getData(),
                'range'=>[5=>5,10=>10,25=>25],
            ]
        );
    }
    public function _getData()
    {
        return Address::nearby($this->person, $this->radius)->orderBy('distance')->get()->toJson();
    }
}
