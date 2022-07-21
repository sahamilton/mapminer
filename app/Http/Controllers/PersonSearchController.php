<?php

namespace App\Http\Controllers;

use App\Person;
use App\User;
use App\Branch;
use App\Track;
use Mail;
use App\Mail\PersonNotification;
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
        // Modified to accomodate staffing specialists
        //$branches = $person->branchesManaged();
        ////
        $branchesServiced = Branch::whereIn('id', $person->getMyBranches())
            ->with('manager')
            ->get();
        
        //note remove manages & manages.servicedby
        $person
            ->load(
                'userdetails.oracleMatch.teamMembers.mapminerUser.roles',
                'managesAccount.countlocations',
                'managesAccount.industryVertical',
                'industryfocus'
            );
        
        if ($branchesServiced) {
            $branchmarkers = $branchesServiced->toJson();
        }
        if (count($person->directReports)>0) {
            $salesrepmarkers = $this->person->jsonify($person->directReports);
        }
        if ($person->userdetails->oracleMatch && $person->userdetails->oracleMatch->teamMembers) {
            $addToMapminer = $person->userdetails->oracleMatch->teamMembers->whereNull('mapminerUser');
        } else {
            $addToMapminer = null;
        }
        return response()->view('persons.details', compact('person', 'branchesServiced', 'branchmarkers',  'user', 'addToMapminer'));
    }

    public function welcome(Person $person)
    {
        $person->load('userdetails');
        if ($person->userdetails->confirmed == 1) {
            Mail::queue(new PersonNotification($person));
            $message = "Welcome email has been sent to " . $person->fullName();
        }else{
            $message = '';
        }
        return redirect()->back()->withMessage($message);
    }
}
