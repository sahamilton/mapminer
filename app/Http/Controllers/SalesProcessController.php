<?php

namespace App\Http\Controllers;
use App\SalesProcess;
use App\Http\Requests\SalesProcessStepRequest;

use Illuminate\Http\Request;

class SalesProcessController extends Controller
{
    public $process;
    public function __construct(SalesProcess $process){

        $this->process = $process;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $process = $this->process->orderBy('sequence')->get();
        return response()->view('salesprocess.index',compact('process'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $process = array();
        return response()->view('salesprocess.create',compact('process'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesProcessStepRequest $request)
    {
        
      
        $this->process->create($request->all());
        return redirect()->route('process.index');

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
        $process = $this->process->findOrFail($id);
        return response()->view('salesprocess.create',compact('process'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SalesProcessStepRequest $request, $id)
    {
        $process = $this->process->findOrFail($id);
        $process->update($request->all());
        return redirect()->route('process.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $this->process->destroy($id);
        return redirect()->route('process.index');

    }
}