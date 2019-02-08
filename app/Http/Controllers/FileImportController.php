<?php

namespace App\Http\Controllers;

use App\FileImport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FileImportController extends Controller
{
    
    public $fileimport;

    public function __construct(FileImport $import){
        $this->fileimport = $import;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imports = $this->fileimport->with('user','user.person')->withCount('addresses')->get();
        return response()->view('fileimports.index',compact('imports'));
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
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FileImport  $fileImport
     * @return \Illuminate\Http\Response
     */
    public function show(FileImport $import)
    {
       
      
       return response()->view('fileimports.show',compact('import'));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FileImport  $fileImport
     * @return \Illuminate\Http\Response
     */
    public function edit(FileImport $fileImport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FileImport  $fileImport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FileImport $fileImport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FileImport  $fileImport
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileImport $fileImport)
    {
        $fileImport->delete();
        return redirect()->route('fileimport.index')->withMessage("Import deleted");
    }

    public function assign(FileImport $import,Request $request){
        $import->assignAddressesToBranches(request('distance'));
        return redirect()->route('fileimport.show',$import->id)->withMessage("Imports assigned");
    }
}
