<?php

namespace App\Http\Livewire;

use App\Models\Location;
Trait NearbyGeocoder {
	
	
	/**
     * [updateAddress description]
     * 
     * @return [type] [description]
     */
    public function updateAddress()
    {
        
        if(! $this->address) {
            $this->_geoCodeHomeAddress();
            session()->flash('error', 'An address is required');
        }
        if ($this->address != $this->location->address) {
           $this->_geoCodeAddress();
        }
    }

    private function _geoCodeAddress()
    {
        $geocode = app('geocoder')->geocode($this->address)->get();
        if(count($geocode) > 0) {
            $this->location->lat = $geocode->first()->getCoordinates()->getLatitude();
            $this->location->lng = $geocode->first()->getCoordinates()->getLongitude();
            $this->location->address = $this->address;
            //update session
            session()->put('geo', ['lat'=>$this->location->lat, 'lng'=>$this->location->lng, 'fulladdress'=>$this->location->address]);



        } else {


            session()->flash('error', 'Cannot geocode address '. $this->address);
            $this->_geoCodeHomeAddress();
        }
            



    }

    private function _geoCodeHomeAddress()
    {
        $geocode = new Location;
        $this->location = $geocode->getMyPosition(); 
        $this->address = $this->location->address; 
    }



}

