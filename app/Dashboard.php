<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    public $manager;
    public $person;
    /**
     * [__construct description]
     * 
     * @param Person $person [description]
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }
    /**
     * Determine number of branches for person
     * 
     * @param Person $person [description]
     * 
     * @return [type]              [description]
     */
    public function checkBranchCount(Person $person)
    {

        $this->manager = $this->person->myTeam($person)->get();

        return $this->manager->map(
            function ($reports) {
                return $reports->branchesServiced->count();
            }
        )->count();
    }
}
