<?php

namespace App\Http\Controllers;

use App\Construction;
use Illuminate\Http\Request;
use GuzzleHttp;

class ConstructionController 
{
    protected $url = 'https://api.constructionmonitor.com/v1/';
    public function index()
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('get', $this->url .'publications/', [
            'auth' => ['hamilton@elaconsultinggroup.com','e7f32326edc8136cf60d34a3cc0674ae'
            ]
        ]);

        
        $collection = collect(json_decode($res->getBody(), true));

        return response()->view('construct.index',compact('collection'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    
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
     * @param  \App\Construction  $construction
     * @return \Illuminate\Http\Response
     */
    public function show(Construction $construction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Construction  $construction
     * @return \Illuminate\Http\Response
     */
    public function edit(Construction $construction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Construction  $construction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Construction $construction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Construction  $construction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Construction $construction)
    {
        //
    }
}
