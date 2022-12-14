<?php

namespace App\Http\Controllers\Exports;

use App\Models\Person;
use Illuminate\Http\Request;
use App\Exports\PeopleDataExport;
use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::withRoles([9])
            ->select('id', 'firstname', 'lastname')
            ->orderBy('lastname')->orderBy('firstname')
            ->get();
        return response()->view('exports.select', compact('people'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $person = Person::findOrFail(request('person'));
        return (new PeopleDataExport($person))->download($person->fullName() . ' data.csv');
    }

    
}
