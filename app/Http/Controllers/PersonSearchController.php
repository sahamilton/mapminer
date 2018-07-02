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
              ->where('user_id','=',$person->user_id)
              ->whereNotNull('lastactivity')
              ->orderBy('created_at','desc')
              ->get();


        //note remove manages & manages.servicedby
        $people = $person
            ->with('directReports',
                'directReports.userdetails.roles',
                'directReports.branchesServiced',
                'reportsTo',
                'userdetails.serviceline',
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
          if($people->has('branchesServiced')){
            $branchmarkers = $people->branchesServiced->toJson();
          }
          if($people->has('directReports')){
            $salesrepmarkers = $this->person->jsonify($people->directReports);
          }

        return response()->view('persons.details',compact('people','track','branchmarkers','salesrepmarkers'));
          }


   
}
