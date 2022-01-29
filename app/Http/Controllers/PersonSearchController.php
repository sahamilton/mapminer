<?php

namespace App\Http\Controllers;

use App\Person;
use App\User;
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
        
        $user = User::withLastLoginId()
            ->withCount('usage')
            ->with(
                'lastLogin', 
                'roles', 
                'serviceline', 
                'scheduledReports',
                'oracleMatch'
            )
            ->find($person->user_id);

        $branches = $person->branchesManaged();

        //note remove manages & manages.servicedby
        $person
            ->load(
                'directReports.userdetails.roles',
                'directReports.userdetails.oracleMatch',
                'managesAccount.countlocations',
                'managesAccount.industryVertical',
                'industryfocus'
            );
        
        if ($branches) {
            $branchmarkers = $branches->toJson();
        }
        if (count($person->directReports)>0) {
            $salesrepmarkers = $this->person->jsonify($person->directReports);
        }

        return response()->view('persons.details', compact('person','branches', 'branchmarkers',  'user'));
    }
}
