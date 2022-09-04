<?php

namespace App\Http\Controllers;

use App\Models\FailedJob;
use Illuminate\Http\Request;

class FailedJobsController extends Controller
{
    public $job;

    public function __construct(FailedJob $job)
    {
        $this->job = $job;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = $this->job->orderBy('failed_at', 'desc')->get();

        return response()->view('failedjobs.index', compact('jobs'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FailedJob $job)
    {
        return response()->view('failedjobs.show', compact('job'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FailedJob $job)
    {
        $job->delete();

        return redirect()->route('failedjobs.index')->withMessage('job record deleted');
    }
}
