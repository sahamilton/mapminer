<?php

namespace App\Http\Controllers;

use App\LeadStatus;
use Illuminate\Http\Request;
use App\Http\Requests\LeadStatusFormRequest;

class LeadStatusController extends Controller
{
    public $leadstatus;

    /**
     * [__construct description]
     * 
     * @param LeadStatus $leadstatus [description]
     */
    public function __construct(LeadStatus $leadstatus)
    {
        $this->leadstatus = $leadstatus;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadstatuses = $this->leadstatus
            ->select('id', 'status')
            ->withCount(['leads'])->get();
 
        return response()->view('leadstatus.index', compact('leadstatuses'));
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
     * @param \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(LeadStatusFormRequest $request)
    {

        $this->leadstatus->create(request()->all());

        return redirect()->route('leadstatus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \ int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(LeadStatus $leadstatus)
    {
        $leadstatus->load('leads', 'leads.leadsource', 'leads.ownedBy');
  

        return response()->view('leadstatus.show', compact('leadstatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id 
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(LeadStatus $leadstatus)
    {
        
        return response()->view('leadstatus.edit', compact('leadstatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * @param int                      $id 
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadStatus $leadstatus)
    {

        $leadstatus->update(request()->except('_method', '_token'));

        return redirect()->route('leadstatus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id 
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadStatus $leadstatu)
    {
        $leadstatus->delete();
        return redirect()->route('leadstatus.index');
    }
}
