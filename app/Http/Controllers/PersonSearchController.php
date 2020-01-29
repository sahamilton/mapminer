<?php

namespace App\Http\Controllers;

use App\Person;
use App\Track;
use Illuminate\Http\Request;

class PersonSearchController extends Controller
{
    protected $person;
    protected $track;

    public function __construct(Person $person, Track $track)
    {
        $this->person = $person;
        $this->track = $track;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find(Person $person)
    {
        $branches = $person->branchesManaged();

        $track = $this->track
            ->where('user_id', $person->user_id)
            ->whereNotNull('lastactivity')
            ->orderBy('created_at', 'desc')
            ->get();

        //note remove manages & manages.servicedby
        $person
            ->load(

                'directReports.userdetails.roles',
                'reportsTo',
                'userdetails.serviceline',
                'userdetails.roles',
                'managesAccount.countlocations',
                'managesAccount.industryVertical',
                'userdetails',
                'industryfocus'
            );

        if ($branches) {
            $branchmarkers = $branches->toJson();
        }
        if (count($person->directReports) > 0) {
            $salesrepmarkers = $this->person->jsonify($person->directReports);
        }

        return response()->view('persons.details', compact('person', 'track', 'branches', 'branchmarkers', 'salesrepmarkers'));
    }
}
