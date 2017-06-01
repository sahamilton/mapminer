<?php

namespace App\Http\Controllers;

use App\LeadStatus;
use Illuminate\Http\Request;
use App\Http\Requests\LeadStatusFormRequest;
class LeadStatusController extends Controller
{
    public $leadstatus;
    public function __construct(LeadStatus $leadstatus){
        $this->leadstatus = $leadstatus;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadstatuses = $this->leadstatus->all();
        return response()->view('leadstatus.index',compact('leadstatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('leadstatus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadStatusFormRequest $request)
    {
        $this->leadstatus->create($request->all());
        return redirect()->route('leadstatus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leadstatus = $this->leadstatus->with('leads')->findOrFail($id);
        return response()->view('leadstatus.show',compact('leadstatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leadstatus = $this->leadstatus->findOrFail($id);
        return response()->view('leadstatus.edit',compact('leadstatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->leadstatus->where('id','=',$id)->update($request->except('_method', '_token'));
        return redirect()->route('leadstatus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $thisleadstatus->destroy($id);
        return redirect()->route('leadstatus.index');
    }
}
