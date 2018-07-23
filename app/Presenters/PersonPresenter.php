<?php

namespace App\Presenters;
use Carbon\Carbon;
use McCool\LaravelAutoPresenter\BasePresenter;

class PersonPresenter extends BasePresenter
{
public function fullName(){
        return $this->wrappedObject->firstname . ' '. $this->wrappedObject->lastname; 
    }
}