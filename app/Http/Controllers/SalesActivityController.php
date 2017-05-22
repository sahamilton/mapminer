<?php

namespace App\Http\Controllers;

use App\Salesactivity;
use App\SearchFilter;
use App\SalesProcess;

use App\Http\Requests\SalesActivityFormRequest;
use Illuminate\Http\Request;

class SalesActivityController extends Controller
{
   
    public $activity;
    public $vertical;
    public $process;

    public function __construct(Salesactivity $activity, SearchFilter $vertical, SalesProcess $process){

        $this->activity = $activity;
         $this->vertical = $vertical; 
         $this->process = $process;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = $this->activity->with('salesprocess','vertical')->get();
        return response()->view('salesactivity.index',compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $verticals = $this->vertical->vertical();
        $process = $this->process->pluck('step','id');

        return response()->view('salesactivity.create',compact('verticals','process'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['from'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['from']);
        $data['to'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['to']);
        $activity = $this->activity->create($data);
        foreach ($request->get('salesprocess') as $process){
            foreach ($request->get('vertical') as $vertical){
                $activity->salesprocess()->attach($process,['vertical_id'=>$vertical]);
            }

        }
        return redirect()->route('salesactivity.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $activity = $this->activity->with('salesprocess','vertical')->findOrFail($id);
        $verticals = $this->vertical->vertical();
        $process = $this->process->pluck('step','id');
        return response()->view('salesactivity.edit',compact('activity','verticals','process'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SalesActivityFormRequest $request, $id)
    {
        $activity = $this->activity->findOrFail($id);
        $data = $request->all();
        $data['from'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['from']);
        $data['to'] = \Carbon\Carbon::createFromFormat('m/d/Y', $data['to']);
        $activity->update($data);

        $activity->salesprocess()->detach();

        foreach ($data['salesprocess'] as $process){
            foreach ($data['vertical'] as $vertical){
                $activity->salesprocess()->attach($process,['vertical_id'=>$vertical]);
            }

        }

        return redirect()->route('salesactivity.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->activity->destroy($id);
        return redirect()->route('salesactivity.index');
    }
}
