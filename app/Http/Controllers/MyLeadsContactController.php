<?php

namespace App\Http\Controllers;

use App\MyLead;
use App\MyLeadContacts;
use Illuminate\Http\Request;

class MyLeadsContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $mylead = MyLead::findOrFail(request('lead_id'));
        $mylead->contacts()->create(request()->all());

        return redirect()->route('myleads.show', $mylead->id)->withMessage('Contact recorded');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MyLeadContacts  $myLeadContacts
     * @return \Illuminate\Http\Response
     */
    public function show(MyLeadContacts $myLeadContacts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MyLeadContacts  $myLeadContacts
     * @return \Illuminate\Http\Response
     */
    public function edit(MyLeadContacts $myLeadContacts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MyLeadContacts  $myLeadContacts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyLeadContacts $myLeadContacts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MyLeadContacts  $myLeadContacts
     * @return \Illuminate\Http\Response
     */
    public function destroy(MyLeadContacts $myLeadContacts)
    {
        //
    }
}
