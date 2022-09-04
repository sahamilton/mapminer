<?php

namespace App\Http\Controllers;

use App\Models\Naics;
use Illuminate\Http\Request;

class NaicsController extends Controller
{
    public $naic;

    public function __construct(Naics $naic)
    {
        $this->naic = $naic;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $naics = $this->naic->whereRaw('CHAR_LENGTH(naics)=2')->get();

        return response()->view('naics.index', compact('naics'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Naics $naic)
    {
        $len = strlen($naic->naics) + 1;

        $naics = $this->naic->where('naics', 'like', $naic->naics.'%')
            ->whereRaw('CHAR_LENGTH(naics)='.$len)->get();

        return response()->view('naics.index', compact('naics'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
