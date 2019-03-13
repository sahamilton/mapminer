<?php

namespace App\Http\Controllers;
use App\Person;
use Illuminate\Http\Request;
use Excel;
use App\Exports\TeamLoginsExport;

class TeamActivityController extends Controller
{
    public $person;

    public function __construct(Person $person)
    {
        $this->person = $person;
    }
   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        $people = $this->getTeamLogins($person);
        
        return response()->view('team.activity',compact('people'));
    }


    public function export(Person $person)
    {
        $people = $this->getTeamLogins($person);
        return Excel::download(new TeamLoginsExport($people), $people->first()->fullName() .'\'s Team logins.csv');
    }
    
    private function getTeamLogins(Person $person)
    {
       //check if in team or can manage people
        $persons = $person->getDescendantsAndSelf();
        return $persons->map(function ($person){
            return $person->load('userdetails','userdetails.usage','userdetails.roles');
        });
    }
}
