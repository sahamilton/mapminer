<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NAMDashboardController extends Controller
{
    public $company;
    public $person;

    /**
     * [__construct description].
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
        //$manager = $this->person->with('managesAccount')->findOrFail(auth()->user()->person->id);
        if (! auth()->user()->hasRole(['admin', 'national_account_manager'])) {
            return redirect()->back()->withError('You do not have the correct role for this view');
        }
        return response()->view('managers.namdashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        if (! auth()->user()->hasRole(['admin', 'national_account_manager'])) {
            return redirect()->back()->withError('You do not have the correct role for this view');
        }
        return response()->view('managers.namsummary');
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

        // we need to find all activities, opportunities, leads
        $branches = Branch::whereHas(

            'locations', function ($q) use ($request) {
                $q->whereIn('company_id', request('account'));
            }
        )->with(
            ['locations'=>function ($q) use ($request) {
                $q->whereIn('company_id', request('account'));
            },
            ]
        )
        ->get();
        dd($branches->first());
        // by branch for these companies
        // get
    }
}
