<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\DataQuality;

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
            
            $dataquality = $this->_dataQualityModel($metric);
            $data[$metric] = $dataquality->count($branch);

        }
        return $data;
    }

    private function _getDataMetrics($branch, $metric)
    {
        $dataquality = $this->_dataQualityModel($metric);
        return $data[$metric] = $dataquality->details($branch);
        //return $data[$metric] = $this->data->$metric(null, $branch->id);
        
    }

    private function _dataQualityModel($metric)
    {
        $model = 'App\DataQuality\DQ'.ucwords($metric);
        return $dataquality = new $model;
    }
}
