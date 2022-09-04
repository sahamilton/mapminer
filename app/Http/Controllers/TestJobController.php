<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\Person;
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
        $mgrs = auth()->user()->person->managers();
        $managers[0] = 'All Managers';
        foreach ($mgrs as $mgr) {
            $managers[$mgr->id] = $mgr->fullName();
        } 
    
        return response()->view('testjob.index', compact('jobs', 'managers'));
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
        
        $reflection = new \ReflectionClass($job);
        $constructor =  $reflection->getConstructor();
        if ($constructor) {
  
            $params = $constructor->getParameters();
            foreach ($params as $param) {


                $data[$job][] =  $param->name;
            }
        }
        if(request('manager')) {
            $manager = Person::findOrFail(request('manager'));
        } else {
            $manager = null;
        }
       
        if (request()->filled('fromdate')) {
   
            $period = $this->_setPeriod($request);
           
            $job::dispatch($period, $manager); 
        } else {
            $campaign = Campaign::findOrFail(19);
           
            $job::dispatch($campaign); 
        }
        
        
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
    private function _getJobs(): Array
    {
        $jobs = collect(File::allFiles(app_path('Jobs')))
            ->map(
                function ($item) {
                    $path = $item->getRelativePathName();
                   
                    $class = strtr(substr($path, 0, strrpos($path, '.')), '/', '\\');

                    return $class;
                }
            );
        foreach ($jobs as $job) {
            $data[$job] = $job;
        }
       
        return $data;
    }
    private function _getJobDependencies(Array $jobs)
    {
        foreach ($jobs as $job) {
            $data[$job] =[];
            $reflection = new \ReflectionClass("App\Jobs\\".$job);
            $constructor =  $reflection->getConstructor();
            if ($constructor) {
                @ray($constructor);
                $params = $constructor->getParameters();
                foreach ($params as $param) {


                    $data[$job][] =  $param->name;
                }
            }
             
        }
        return $jobs;
    }
    private function _setPeriod(Request $request)
    {
        return ['from'=>Carbon::parse(request('fromdate')), 'to'=>Carbon::parse(request('todate'))];
    }
    

    private function _getManager(Request $request)
    {
       
        if (request('manager') != 0)
        {
            return Person::findOrFail(request('manager'));
        }
        dd(request()->all());
    }
}
