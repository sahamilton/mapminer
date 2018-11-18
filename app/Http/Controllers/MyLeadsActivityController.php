<?php

namespace App\Http\Controllers;
use App\MyLead;
use App\MyLeadActivity;
use Illuminate\Http\Request;

class MyLeadsActivityController extends Controller
{
    public $mylead;
    public $activity;
    public function __construct(MyLead $mylead,MyLeadActivity $activity){
        $this->activity = $activity;
        $this->mylead = $mylead;
    }

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
        $data = array_merge(request()->all(), ['user_id' => auth()->user()->id]);
        
        $mylead->relatedNotes()->create($data);

        return redirect()->route('myleads.show',$mylead->id)->withMessage('Activity recorded');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MyLeadActivity  $myLeadActivity
     * @return \Illuminate\Http\Response
     */
    public function show(MyLeadActivity $myLeadActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MyLeadActivity  $myLeadActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(MyLeadActivity $myLeadActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MyLeadActivity  $myLeadActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyLeadActivity $myLeadActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MyLeadActivity  $myLeadActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(MyLeadActivity $myLeadActivity)
    {
        //
    }
}
