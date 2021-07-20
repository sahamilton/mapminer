<?php

namespace App\Http\Controllers\Imports;

use App\Branch;
use App\BranchLeadImport;
use App\Http\Requests\BranchImportFormRequest;
use App\Imports;
use Illuminate\Http\Request;

class BranchLeadImportController extends ImportController
{
    public $branch;
    protected $serviceline;
    public $userServiceLines;
    public $import;
    public $importtable = 'branchesimport';

    public function __construct(Branch $branch, BranchLeadImport $branchleadimport)
    {
        $this->import = $branchleadimport;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requiredFields = $this->import->requiredFields;

        return response()->view('imports.branchleads.import', compact('requiredFields'));
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
     * @param  \App\BranchLeadImport  $branchLeadImport
     * @return \Illuminate\Http\Response
     */
    public function show(BranchLeadImport $branchLeadImport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BranchLeadImport  $branchLeadImport
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchLeadImport $branchLeadImport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BranchLeadImport  $branchLeadImport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchLeadImport $branchLeadImport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BranchLeadImport  $branchLeadImport
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchLeadImport $branchLeadImport)
    {
        //
    }
}
