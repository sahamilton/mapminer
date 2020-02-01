<?php

namespace App\Http\Controllers;

use App\Branch;
use App\DataQuality;
use Illuminate\Http\Request;

class DataQualityController extends Controller
{
    public $data;
    public $branch;
   
    /**
     * [__construct description]
     * 
     * @param DataQuality $data   [description]
     * @param Branch      $branch [description]
     */
    public function __construct(DataQuality $data, Branch $branch)
    {
        $this->data = $data;
        $this->branch = $branch;
        $this->metrics = $this->data->getMetrics();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = auth()->user()->person->myBranches();

        if (! session('branch')) {
            $branch = $this->branch->findOrFail(array_keys($branches)[0]);
            session(['branch' => $branch->id]);
        } else {
            $branch = $this->branch->findOrFail(session('branch'));
        }
        
        $data = $this->_getSummaryDataMetrics($branch);

        return response()->view(
            'dataquality.index', 
            [
            'metrics'=>$this->metrics,
            'data'=> $data, 
            'branches'=>$branches,
            'branch'=>$branch
            ]
        );
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
     * @param  \App\DataQuality  $dataQuality
     * @return \Illuminate\Http\Response
     */
    public function show(DataQuality $dataQuality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DataQuality  $dataQuality
     * @return \Illuminate\Http\Response
     */
    public function edit(DataQuality $dataQuality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DataQuality  $dataQuality
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataQuality $dataQuality)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DataQuality  $dataQuality
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataQuality $dataQuality)
    {
        //
    }
    /**
     * [branch description]
     * 
     * @param Request $request [description]
     * 
     * @return [type]           [description]
     */
    public function branch(Request $request)
    {

        $branches = auth()->user()->person->myBranches();
        $branch = $this->branch->findOrFail(request('branch'));
        session(['branch'=>$branch->id]);
        $data = $this->_getSummaryDataMetrics($branch);
        return response()->view(
            'dataquality.index', 
            [
            'metrics'=>$this->metrics,
            'data'=> $data, 
            'branches'=>$branches,
            'branch'=>$branch
            ]
        );
    }


    public function details(Request $request)
    {
        
        $branches = auth()->user()->person->myBranches();
        $branch = $this->branch->findOrFail(request('branch'));
        $data = $this->_getDataMetrics($branch, request('metric'));
        
        return response()->view(
            'dataquality.'. strtolower(request('metric')), 
            [
            'metric'=>request('metric'),
            'data'=> $data, 
            'branches'=>$branches,
            'branch'=>$branch
            ]
        );

    }
    private function _getSummaryDataMetrics($branch)
    {
        foreach ($this->metrics as $metric) {
            $data[$metric] = $this->data->$metric('count', $branch->id);
        }
        return $data;
    }

    private function _getDataMetrics($branch, $metric)
    {
        
        return $data[$metric] = $this->data->$metric(null, $branch->id);
        
    }
}
