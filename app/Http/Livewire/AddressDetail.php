<?php

namespace App\Http\Livewire;
use App\Models\Address;
use Livewire\Component;

class AddressDetail extends Component
{
    public $view='summary';
    public $address;

    public function mount(Address $address)
    {
        
        $this->address = $address;
    }

    public function render()
    {
        return view('livewire.address-detail', [

            'address'=>$this->_getAddressDetails(),
            'views'=>[
                'summary'=>'Details',
                'contacts'=> 'Contacts',
                'activities'=>"Activities",
                 'opportunities'=>'Opportunities',]

            ]
        );
    }

    public function convert()
    {
        if ($this->address->isCustomer) {
            $this->address->update(['isCustomer'=>false]);
        } else {
             $this->address->update(['isCustomer'=>true]);
        }
    }

    private function _getAddressDetails()
    {
        switch($this->view) {
            case 'summary':
                $address = Address::query()
                    ->withCount('contacts', 'activities', 'opportunities')
                    ->get();
                break;

            case 'contacts':
                $address = Address::query()
                    ->join('contacts', 'addresses.id', '=', 'contacts.address_id')
                    ->get();
                break;

            case 'activities':

                break;

            case 'opportunities':

                break;


        }
        return $address;
    }
}
