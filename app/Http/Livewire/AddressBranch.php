<?php

namespace App\Http\Livewire;
use App\Address;
use Livewire\Component;

class AddressBranch extends Component
{
    public $address;
    
    public function mount(Address $address)
    {
        
        $this->address = $address;
    }
    public function render()
    {
        return view(
            'livewire.address-branch', [

                'rank'=>$this->address->currentRating(),
                'ranks'=>[1,2,3,4,5],
                
            ]
        );
    }

    /// rate
    public function ranking($rank)
    {
       
        $person_id = auth()->user()->person->id;
        
        $this->address->ranking()->sync([$person_id=>['ranking'=>$rank]]);

    }
    /// 
    public function accept()
    {

        //$this->address->
    }
    /// 
    public function decline()
    {

    }
    /// 
    /// 
    public function transfer(Branch $branch)
    {

    }
}
