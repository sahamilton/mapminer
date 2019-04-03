<?php

namespace App\Http\Controllers;

use App\DatabaseBackupManager;
use Illuminate\Http\Request;

class DatabaseBackupManagerController extends Controller
{
    public $database;

    public function __construct(DatabaseManager $database)
    {

        $this->database = $database;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(storage_path());
        $backups = $this->database->allFiles();
      
        return response()->view('database.index',compact('backups'));
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
     * @param  \App\DatabaseManager  $databaseManager
     * @return \Illuminate\Http\Response
     */
    public function show(DatabaseManager $databaseManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DatabaseManager  $databaseManager
     * @return \Illuminate\Http\Response
     */
    public function edit(DatabaseManager $databaseManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DatabaseManager  $databaseManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DatabaseManager $databaseManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DatabaseManager  $databaseManager
     * @return \Illuminate\Http\Response
     */
    public function destroy($file)
    {
       //dd(storage_path($this->database->directory .$file));
       unlink(storage_path($this->database->backupDirectory."\\" .$file));
        return redirect()->route('database.index')->withMessage("Backup deleted");

       
    }
}
