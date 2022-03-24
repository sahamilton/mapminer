<?php

namespace App\Http\Livewire;
use App\Address;
use Livewire\Component;

class AddressDetail extends Component
{
    
    public $address;

    public function mount(Address $address)
    {
        
        $this->address = $address;
    }

    public function render()
    {
        return view('livewire.address-detail');
    }

    public function convert()
    {
        if ($this->address->isCustomer) {
            $this->address->update(['isCustomer'=>false]);
        } else {
             $this->address->update(['isCustomer'=>true]);
        }
    }
}
