<?php

namespace App\Http\Controllers;
use App\Person;
use App\Track;
use Illuminate\Http\Request;

class PersonSearchController extends Controller
{   
    protected $person;
    protected $track;
    public function __construct(Person $person,Track $track){
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
       
      $track = $this->track
      ->where('user_id','=',$person->user_id)->orderBy('created_at','desc')->get();


        //note remove manages & manages.servicedby
        $people = $person
            ->with('directReports',
                'directReports.userdetails.roles',
                'directReports.branchesServiced',
                'reportsTo',
                'managesAccount',
                'managesAccount.countlocations',
                'managesAccount.industryVertical',
                'userdetails',
                'industryfocus',
                'userdetails.roles',
                'branchesServiced',
                'branchesServiced.servicedBy'
                        )

            ->findOrFail($person->id);
           
        return response()->view('persons.details',compact('people','track'));
    }

   
}
