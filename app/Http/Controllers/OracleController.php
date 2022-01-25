<?php

namespace App\Http\Controllers;

use App\Oracle;
use App\Http\Requests\StoreoracleRequest;
use App\Http\Requests\UpdateoracleRequest;

class OracleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('oracle.index');
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
     * @param  \App\Http\Requests\StoreoracleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOracleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\oracle  $oracle
     * @return \Illuminate\Http\Response
     */
    public function show(Oracle $oracle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\oracle  $oracle
     * @return \Illuminate\Http\Response
     */
    public function edit(oracle $oracle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateoracleRequest  $request
     * @param  \App\oracle  $oracle
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateoracleRequest $request, oracle $oracle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\oracle  $oracle
     * @return \Illuminate\Http\Response
     */
    public function destroy(oracle $oracle)
    {
        //
    }
}
