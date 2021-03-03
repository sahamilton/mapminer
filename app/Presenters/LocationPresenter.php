<?php

namespace App\Presenters;

use Carbon\Carbon;
use McCool\LaravelAutoPresenter\BasePresenter;

class LocationPresenter extends BasePresenter
{
    public function phone()
    {
        $phoneNumber = $this->wrappedObject->phone;
        if (! empty($phoneNumber)) {
            if (! strpos($phoneNumber, ')')) {
                return '('.substr($phoneNumber, 0, 3).') '.substr($phoneNumber, 3, 3).'-'.substr($phoneNumber, 6);
            } else {
                return $phoneNumber;
            }
        } else {
            return $phoneNumber;
        }
    }

    public function phone_number()
    {
        if ($phoneNumber = $this->wrappedObject->phone_number) {
            if (! strpos($phoneNumber, ')')) {
                return '('.substr($phoneNumber, 0, 3).') '.substr($phoneNumber, 3, 3).'-'.substr($phoneNumber, 6);
            } else {
                return $phoneNumber;
            }
        }
    }
/*
    public function fullAddress()
    {
        if ($this->wrappedObject->street) {
            return $this->wrappedObject->street.' '.$this->wrappedObject->city.' '.$this->wrappedObject->state.' '.$this->wrappedObject->zip;
        } else {
            return $this->wrappedObject->address.' '.$this->wrappedObject->city.' '.$this->wrappedObject->state.' '.$this->wrappedObject->zip;
        }
    }

    public function fullName()
    {
        return $this->wrappedObject->firstname.' '.$this->wrappedObject->lastname;
    }
    */
}
