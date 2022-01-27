<?php

namespace App\Http\Controllers;

use App\Oracle;


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
     * Display the specified resource.
     *
     * @param  \App\oracle  $oracle
     * @return \Illuminate\Http\Response
     */
    public function show(Oracle $oracle)
    {
        $oracle->load('teamMembers', 'oracleManager', 'mapminerUser', 'mapminerManager');
        return response()->view('oracle.show', compact('oracle'));
    }

    /**
     * [unmatched description]
     * @return [type] [description]
     */
    public function unmatched()
    {
        return response()->view('oracle.matched');
    }
}
