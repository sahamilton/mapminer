<?php

namespace App\Http\Controllers;

use App\Construction;
use Illuminate\Http\Request;
use GuzzleHttp;
use App\Branch;


class ConstructionController  extends BaseController
{
    public $construction;
    
    public function __construct(Construction $construction){
        $this->construction = $construction;
        parent::__construct($construction);
    }
    
    public function index()
    {
        $projects = array();
        return response()->view('construct.index',compact('projects'));
    }

    
    

    public function search(Request $request){
 
        $data = $request->except('_token');

        $geoCode = app('geocoder')->geocode($data['address'])->get();
        $data['location'] =$this->construction->getGeoCode($geoCode);
        session()->put('geo',$data['location']);
        
        if($data['view'] =='list'){
            $projects = $this->construction->getProjectData($data);
            return response()->view('construct.index',compact('projects','data'));
        }else{
            $data = $this->construction->getMapData($data);
            return response()->view('construct.map',compact('data'));
        }
        
    }
    

    /**
    /    Create XML of nearby construction projects for mapping.
    /
    /
    **/
    public function map($distance,$latlng){

        $data = $this->construction->getMapParameters($distance,$latlng);
        $projects = $this->construction->getProjectData($data);

        return response()->view('construct.xml',compact('projects'));


    }
    public function show($id)
    {

        $project = $this->construction->getProject($id);
        // move to model?
        $construction = new Construction;
        $construction->lat = $project['location']['lat'];
        $construction->lng = $project['location']['lon'];
        $construction->id = $project['id'];
        // move to Branch model?
        $branches = Branch::whereHas('servicelines', function ($q) {
                $q->whereIn('servicelines.id',$this->userServiceLines);
            })
            ->nearby($construction,'100')
            ->limit(5)
            ->get();
        
        return response()->view('construct.show',compact('project','branches'));
    }

    
}
