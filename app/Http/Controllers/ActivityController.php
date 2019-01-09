<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\ActivityFormRequest;

class ActivityController extends Controller
{
    public $activity;
    public $contact;

    public function __construct(Activity $activity,Contact $contact){
        $this->activity = $activity;
        $this->contact = $contact;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = $this->activity->myActivity()->get();
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
    public function store(ActivityFormRequest $request)
    {
      
        $data = $this->parseData($request);
        $activity = Activity::create($data);
        if($data['contact_id']){
            $contact = $this->contact->findOrFail($data['contact_id']);
            $activity->relatedContact()->attach($data);
        }
        return redirect()->route('address.show',$data['location_id']);
    }

    private function parseData($request){
        $data= $request->except(['_token','submit']);
        $data['activity_date'] = Carbon::parse($data['activity_date']);
        if($data['followup_date']){
            $data['followup_date'] = Carbon::parse($data['followup_date']);
        }
        $data['address_id'] = request('location_id');
        $data['user_id'] = auth()->user()->id;
        $data['contact_id'] = request('contact_id');
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
