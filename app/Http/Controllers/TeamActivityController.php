<?php

namespace App\Http\Controllers;

use App\Exports\MyTeamLoginsExport;
use App\Person;
use Excel;
use Illuminate\Http\Request;

class TeamActivityController extends Controller
{
    public $person;

    /**
     * [__construct description].
     *
     * @param Person $person [description]
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * [index description].
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
     * [show description].
     *
     * @param Person $person [description]
     *
     * @return [type]         [description]
     */
    public function show(Person $person)
    {
        if ($people = $this->_getTeamLogins($person)) {
            return response()->view('team.activity', compact('people'));
        } else {
            return redirect()->route('home')
                ->withWarning($person->fullName().' is not a member of your team');
        }
    }

    /**
     * [export description].
     *
     * @param Person $person [description]
     *
     * @return [type]         [description]
     */
    public function export(Person $person)
    {
        if ($people = $this->_getTeamLogins($person)) {
            return Excel::download(
                new MyTeamLoginsExport($people), $people->first()->fullName().'\'s Team logins.csv'
            );
        }

        return redirect()->route('home')
            ->withWarning($person->fullName().' is not a member of your team');
    }

    /**
     * [getTeamLogins description].
     *
     * @param Person $person [description]
     *
     * @return [type]         [description]
     */
    private function _getTeamLogins(Person $person)
    {
        $myTeam = $this->person->where(
            'user_id', '=', auth()->user()->id
        )->firstOrFail()
            ->descendantsAndSelf()->pluck('id')->toArray();

        if (! in_array($person->id, $myTeam)
            && ! auth()->user()->hasRole('admin')
        ) {
            return false;
        } else {
            $persons = $person->getDescendantsAndSelf();

            return $persons->map(
                function ($person) {
                    return $person->load('userdetails', 'userdetails.usage', 'userdetails.roles');
                }
            );
        }
    }
}
