<?php

namespace App;

trait Addressable
{
    /**
     * [address description].
     *
     * @return [type] [description]
     */
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * [allStates description].
     *
     * @param [type] $type [description]
     *
     * @return [type]       [description]
     */
    public function allStates($type)
    {
        return  Address::where('addressable_type', '=', $type)
            ->select('state')
            ->distinct()->get();
    }
}
