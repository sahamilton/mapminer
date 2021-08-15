<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class TestJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all jobs
        $jobs = $this->_getJobs();
        return response()->view('testjob.index', compact('jobs'));
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
        
        $job = request('job');
        $job = "\App\Jobs\\" . $job;
        $job::dispatch();
        return redirect()->back()->withMessage($job . ' has been dispatched');
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
        //
    }
    private function _getJobs(): Collection
    {
        return collect(File::allFiles(app_path('jobs')))
            ->map(
                function ($item) {
                    $path = $item->getRelativePathName();
                    $class = sprintf(
                        '\%s%s',
                        \Illuminate\Container\Container::getInstance()->getNamespace(),
                        strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                    );

                    return $class;
                }
            );
    }
}
