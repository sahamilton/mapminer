<?php

namespace App\Http\Controllers;

use App\ActivityType;
use App\Person;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    public $activitytype;

    public function __construct(ActivityType $activitytype, Person $person)
    {
        $this->activitytype = $activitytype;
        $this->person = $person;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activitytypes = $this->activitytype->withCount('activities')->get();

        return response()->view('activitytypes.index', compact('activitytypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('activitytypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $type = $this->activitytype->create(request()->except('_token'));

        return redirect()->route('activitytype.index')->withSuccess('New Activity Type Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ActivityType  $activityType
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityType $activityType)
    {
        $activityType = $this->activitytype->with('activities')
                ->findOrFail($activityType->id);
        $users = $activityType->activities->pluck('id', 'user_id')->toArray();
        $people = $this->person->whereIn('user_id', array_keys($users))->with(['activities'=>function ($q) use ($activityType) {
            $q->where('activitytype_id', '=', $activityType->id);
        }])->with('userdetails', 'userdetails.roles')->get();

        return response()->view('activitytypes.show', compact('activityType', 'people'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ActivityType  $activityType
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityType $activitytype)
    {
        return response()->view('activitytypes.edit', compact('activitytype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ActivityType  $activityType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActivityType $activityType)
    {
        
        $activityType->update(request()->except('_token'));

        return redirect()->route('activitytype.index')->withSuccess('Activity Type Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ActivityType  $activityType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityType $activityType)
    {
        $activityType->delete();

        return redirect()->route('activitytype.index')->withWarning('Activity Type Deleted');
    }
}
