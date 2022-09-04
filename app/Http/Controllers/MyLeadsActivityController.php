<?php

namespace App\Http\Controllers;

use App\Models\AddressBranch;
use Carbon\Carbon;
use App\Models\MyLeadActivity;
use Illuminate\Http\Request;

class MyLeadsActivityController extends Controller
{
    public $mylead;
    public $activity;
    public function __construct(AddressBranch $mylead, MyLeadActivity $activity)
    {
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
        $branches = auth()->user()->person->myBranches();
        $leads = $this->mylead
            ->whereIn('branch_id', array_keys($branches))
            ->get();
        dd($leads, $branches);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $this->cleanseData($request);
       
        $this->activity->create($data);
        return redirect()->route('myleads.show', request('lead_id'))->withMessage('Activity recorded');
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
        $lead = $myLeadActivity->related_id;
        if ($myLeadActivity->delete()) {
            return redirect()->route('myleads.show', $lead)->withMessage('Activity deleted');
        } else {
            return redirect()->route('myleads.show', $lead)->withError('Unable to delete');
        }
    }

    private function cleanseData(Request $request)
    {
        $data =['user_id' => auth()->user()->id,
        'related_id'=> request('lead_id'),
        'type'=>'mylead',
        'activity'=>request('activity'),
        'activity_date'=>Carbon::parse(request('activitydate'))];
        if (request()->has('followupdate')) {
            $data['followup_date'] = Carbon::parse(request('followupdate'));
        }
       
        return array_merge(request()->all(), $data);
    }
}
