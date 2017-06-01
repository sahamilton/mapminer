<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeadSource;
use App\Http\Requests\LeadSourceFormRequest;

class LeadSourceController extends Controller
{
    public $leadsource;
    public function __construct(LeadSource $leadsource){
        $this->leadsource = $leadsource;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadsources = $this->leadsource->with('leads')->get();
        return response()->view('leadsource.index', compact('leadsources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return response()->view('leadsource.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadSourceFormRequest $request)
    {
        $this->leadsource->create($request->all());
        return redirect()->route('leadsource.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leadsource = $this->leadsource->with('leads')->findOrFail($id);
        return response()->view('leadsource.show',compact('leadsource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leadsource = $this->leadsource->with('leads')->findOrFail($id);
        return response()->view('leadsource.edit',compact('leadsource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeadSourceFormRequest $request, $id)
    {
        $leadsource= $this->leadsource->findOrFail($id);
        $leadsource->update($request->all());
        return redirect()->route('leadsource.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->leadsource->destroy($id);
        return redirect()->route('leadsource.index');
    }
}
