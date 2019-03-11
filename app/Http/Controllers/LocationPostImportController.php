<?php

namespace App\Http\Controllers;

use App\LocationPostImport;
use Illuminate\Http\Request;
use App\Company;

class LocationPostImportController extends Controller
{
    public $company;
    public $import;
    public function __construct(LocationPostImport $import, Company $company)
    {
        $this->company = $company;
        $this->import = $import;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $import = $this->import->first();

        $company = $this->company->findOrFail($import->company_id);
        $data = $this->import->returnAddressMatchData($company);
        return response()->view('location.imports',compact('data'));
    }

        public function adddelete(Request $request)
    {
       if(request()->has('delete')){
            $this->deleteLocations(request('delete'));
       }
        
        $this->addNewLocations(request('add'));
        return redirect()->route('locations.process');
    }

    
    private function addNewLocations($data)
    {
       /* insert into table
        update import_ref
        if contacts
            add contacts
            delete from import table*/
 
        $company = Company::findOrFail('275');

        $import = new LocationImport($company);
        $insert = $import->whereIn('id',$data)->get();
        dd($insert);
       // $insert = \DB::table($this->temptable);
        $insert = $this->setimport_ref($insert);
        dd($insert->toArray());
        \DB::table($this->table)->insert($insert);
    }

    private function updateLocations($data)
    {
        
        

        /*update table
        if contacts
            add contacts
        delete from import table*/
    }

    private function deleteLocations($data)
    {
    
       return  \DB::table($this->table)->whereIn('id',$data)->delete();
    }

      private function setimport_ref($collection)
    {
        $collection->map(function ($item)
        {
            $item->import_ref = $item->id;
            $item->user_id = auth()->user()->id;
           
            return $item;
        });

        return $collection;
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
     * @param  \App\LocationPostImport  $locationPostImport
     * @return \Illuminate\Http\Response
     */
    public function show(LocationPostImport $locationPostImport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LocationPostImport  $locationPostImport
     * @return \Illuminate\Http\Response
     */
    public function edit(LocationPostImport $locationPostImport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LocationPostImport  $locationPostImport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LocationPostImport $locationPostImport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LocationPostImport  $locationPostImport
     * @return \Illuminate\Http\Response
     */
    public function destroy(LocationPostImport $locationPostImport)
    {
        //
    }
}
