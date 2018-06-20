<?php

namespace App\Presenters;
use Carbon\Carbon;
use McCool\LaravelAutoPresenter\BasePresenter;

class LocationPresenter extends BasePresenter
{
    public function phone()
    {
        $phoneNumber = $this->wrappedObject->phone;

        if(! strpos($phoneNumber,")")){
        	
        	return "+1 (".substr($phoneNumber, 0, 3).") ".substr($phoneNumber, 3, 3)."-".substr($phoneNumber,6);
        }else{
        	
        	return "+1 ".$phoneNumber;
        }
       
    }

    public function phone_number()
    {
        if($phoneNumber = $this->wrappedObject->phone_number){


        if(! strpos($phoneNumber,")")){
            
            return "(".substr($phoneNumber, 0, 3).") ".substr($phoneNumber, 3, 3)."-".substr($phoneNumber,6);
        }else{
            
            return $phoneNumber;
        }
       }
    }

    public function fullAddress(){
        return $this->wrappedObject->address . ' '. $this->wrappedObject->city .'' . $this->wrappedObject->state . ' '  . $this->wrappedObject->zipi;
    }

    public function fullName(){
        return $this->wrappedObject->firstname . ' '. $this->wrappedObject->lastname; 
    }
}