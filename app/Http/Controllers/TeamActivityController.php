<?php

namespace App\Http\Controllers;
use App\Person;
use Illuminate\Http\Request;
use Excel;
use App\Exports\MyTeamLoginsExport;

class TeamActivityController extends Controller
{
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
     * [index description]
     * 
     * @return [type] [description]
     */
    public function index()
    {
        $person = $this->person->where(
            'user_id', '=', auth()->user()->id
        )->firstOrFail();
        return redirect()->route('team.show', $person->id);
    }

    /**
     * [show description]
     * 
     * @param Person $person [description]
     * 
     * @return [type]         [description]
     */
    public function show(Person $person)
    {
        if (! $this->_inMyTeam($person)) {
            return redirect()->route('home')
                ->withWarning($person->fullName() . " is not a member of your team");
        } else {
            return response()->view('team.activity', compact('person'));
        }
    }

    /**
     * [export description]
     * 
     * @param Person $person [description]
     * 
     * @return [type]         [description]
     */
    public function export(Person $person)
    {
        if ($people = $this->_getTeamLogins($person)) {
            
            return Excel::download(
                new MyTeamLoginsExport($people), $people->first()->fullName() . '\'s Team logins.csv'
            );
        }
        return redirect()->route('home')
            ->withWarning($person->fullName() . " is not a member of your team");
       
    }
    /**
     * [getTeamLogins description]
     * 
     * @param Person $person [description]
     * 
     * @return [type]         [description]
     */
    private function _getTeamLogins(Person $person)
    {
        
        if (! $this->_inMyTeam($person)) {
            return false;
        }

        $persons = $person->getDescendantsAndSelf();
            return $persons->map(
                function ($person) {
                    return $person->load('userdetails', 'userdetails.roles');
                }
            );
        dd($persons->first());
    }
    /**
     * [_inMyTeam description]
     * 
     * @param  Person $person [description]
     * @return [type]         [description]
     */
    private function _inMyTeam(Person $person)
    {
        if (! auth()->user()->hasRole('admin')) {
            $team = $person->getDescendantsAndSelf()->pluck('id')->toArray();
            if (! in_array(auth()->user()->person->id, $team)) {
                return false;
            }
        }
        return true;
    }
}
