<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailLog;

class EmailLogController extends Controller
{
    protected $emaillog;

    public function __construct(EmailLog $emaillog) {
        $this->emaillog = $emaillog;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = $this->emaillog->with('user','user.member')->get();
     
        return response()->view('emails.logs',compact('logs'));

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
        $this->emaillog->destroy($id);
        return redirect()->back();
    }

    public function destroychecked(Request $request)
    {
        if ($request->has('ids')) {
            $deleteIds = explode(',',request('ids'));
        
        }
        $this->emaillog->destroy($deleteIds);
        return redirect()->back();
    }
}
