<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    public $manager;
    public $person;

    public function __construct(Person $person)
    {
    	$this->person = $person;
    }
    public function checkBranchCount(Person $person=null)
    {


    $this->manager = $this->person->myTeam($person)->get();        
    return $this->manager->map(function ($reports){
            return $reports->branchesServiced->count();
        })->count();
    }
}
