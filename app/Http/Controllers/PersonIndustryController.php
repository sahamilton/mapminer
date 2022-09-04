<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\PersonIndustry;
use App\Models\SearchFilter;
use Illuminate\Http\Request;

class PersonIndustryController extends Controller
{
    protected $person;
    protected $industry;
    protected $searchfilter;

    public function __construct(PersonIndustry $industry, SearchFilter $searchfilter, Person $person)
    {
        $this->industry = $industry;
        $this->searchfilter = $searchfilter;
        $this->person = $person;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $industries = $this->industry->with('industry')->where('person_id', '=', auth()->user()->person->id)->get()->pluck('industry.id')->toArray();

        $filters = $this->searchfilter->where('filter', '=', 'Industry & Segments')->first()->getDescendantsAndSelf();

        return response()->view('industries.index', compact('industries', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $person = $this->person->findOrFail(request('id'));
        $person->industryfocus()->sync(request('vertical'));

        return redirect()->route('user.show', auth()->user()->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PersonIndustry  $personIndustry
     * @return \Illuminate\Http\Response
     */
    public function show(PersonIndustry $personIndustry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PersonIndustry  $personIndustry
     * @return \Illuminate\Http\Response
     */
    public function edit(PersonIndustry $personIndustry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PersonIndustry  $personIndustry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PersonIndustry  $personIndustry
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonIndustry $personIndustry)
    {
        //
    }
}
