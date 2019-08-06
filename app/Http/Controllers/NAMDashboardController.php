<?php

namespace App\Http\Controllers;
use App\Person;
use App\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NAMDashboardController extends Controller
{
    public $company;
    public $person;
    /**
     * [__construct description]
     * 
     * @param Company $company [description]
     * @param Person  $person  [description]
     */
    public function __construct(
        Company $company, 
        Person $person
    ) {
        $this->company = $company;
        $this->person = $person;     
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $manager = $this->person->with('managesAccount')->findOrFail(auth()->user()->person->id);
        return response()->view('managers.namdashboard', compact('manager'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $period['from'] = Carbon::now()->subMonth();
        $period['to'] = Carbon::now();
        $company = $this->company->summaryStats($period)
            ->where('id', request('account'))->firstOrFail();
        dd($company);
    }
}
