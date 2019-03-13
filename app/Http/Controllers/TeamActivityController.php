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
   
    public function index(){
        $person = $this->person->where('user_id','=',auth()->user()->id)->firstOrFail();
        return redirect()->route('team.show',$person->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
       if($people = $this->getTeamLogins($person)){
        
            return response()->view('team.activity',compact('people'));
        }else{
            return redirect()->route('home')->withWarning($person->fullName() . " is not a member of your team");
        }
    }


    public function export(Person $person)
    {
        if($people = $this->getTeamLogins($person)){
            
             return Excel::download(new TeamLoginsExport($people), $people->first()->fullName() .'\'s Team logins.csv');
        }
        return redirect()->route('home')->withWarning($person->fullName() . " is not a member of your team");
       
    }
    
    private function getTeamLogins(Person $person)
    {
       //check if in team or can manage people
       //
       $myTeam = $this->person->where('user_id','=',auth()->user()->id)->firstOrFail()
                ->descendantsAndSelf()->pluck('id')->toArray();
        
        if(! in_array($person->id,$myTeam) && ! auth()->user()->hasRole('admin')){

            return false;
        }else{

            $persons = $person->getDescendantsAndSelf();
            return $persons->map(function ($person){
                return $person->load('userdetails','userdetails.usage','userdetails.roles');
            });
        }
    }
}
