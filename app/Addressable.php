<?php

namespace App;

trait Addressable
{

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function allStates($type)
    {

        return  Address::where('addressable_type', '=', $type)->select('state')->distinct()->get();
    }
}
