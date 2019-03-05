<?php

namespace App\Http\Controllers;

use App\InboundMail;
use Illuminate\Http\Request;

class InboundMailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        validate that it is coing from approved i
        validate that it can be parsed
        parse and store
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InboundMail  $inboundMail
     * @return \Illuminate\Http\Response
     */
    public function show(InboundMail $inboundMail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InboundMail  $inboundMail
     * @return \Illuminate\Http\Response
     */
    public function edit(InboundMail $inboundMail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InboundMail  $inboundMail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InboundMail $inboundMail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InboundMail  $inboundMail
     * @return \Illuminate\Http\Response
     */
    public function destroy(InboundMail $inboundMail)
    {
        //
    }
}
