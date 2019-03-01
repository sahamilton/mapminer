<?php

namespace App\Http\Controllers;

use App\Construction;
use Illuminate\Http\Request;
use GuzzleHttp;
use App\Branch;

class ConstructionController extends BaseController
{
    public $construction;
    public $branch;
    
    public function __construct(Construction $construction, Branch $branch)
    {
        $this->construction = $construction;
        $this->branch = $branch;
        parent::__construct($construction);
    }
    
    public function index()
    {
        $projects = [];
        return response()->view('construct.index', compact('projects'));
    }

    
    

    public function search(Request $request)
    {
 

        $data = request()->except('_token');

        $geoCode = app('geocoder')->geocode($data['address'])->get();
        $data['location'] =$this->construction->getGeoCode($geoCode);
        session()->put('geo', $data);

        
        if ($data['view'] =='list') {
            $projects = $this->construction->getProjectData($data);
            return response()->view('construct.index', compact('projects', 'data'));
        } else {
            $data = $this->construction->getMapData($data);
            return response()->view('construct.map', compact('data'));
        }
    }
    

    /**
    /    Create XML of nearby construction projects for mapping.
    /
    /
    **/
    public function map($distance, $latlng)
    {

        $data = $this->construction->getMapParameters($distance, $latlng);
        $projects = $this->construction->getProjectData($data);

        return response()->view('construct.xml', compact('projects'));
    }
    


    public function show($id)
    {

        $project = $this->construction->getProject($id);
      
        $construction = $this->construction->makeConstruction($project);
        if (! isset($project['location'])) {
            $project['location']['lat'] = $construction->lat;
            $project['location']['lon'] = $construction->lng;
        }
    
        $branches = $this->branch->getNearByBranches($this->userServiceLines, $construction);
            
        return response()->view('construct.show', compact('project', 'branches'));
    }

    public function company($id)
    {

        $company = $this->construction->getCompany($id);
        return response()->view('construct.companyshow', compact('company'));
    }
}
