<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public $project;

    public function __construct(Project $projects){

        $this->project = $projects;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = $this->project->get();
        return response()->view('projects.index',compact('projects'));
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
        $project = $this->project->with('companies')->findOrFail($id);
        return response()->view('projects.show',compact("project"));
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

    public function geocodeProjeccts(){


    }

    public function findNearbyProjects($distance,$latlng){

        $geo =explode(":",$latlng);
        $lat=$geo[0];
        $lng=$geo[1];

        $limit=100;
        $result = $this->project->findNearbyProjects($lat,$lng,$distance,$limit);
       return  $this->makeNearbyProjectsXML($result);
        
    }

    public function mapProjects(){

        $data['lat'] = '40.1492';
        $data['lng'] = '-86.2595';
        
        $data['distance']= 20;
        $data['latlng'] = $data['lat'] . ":".$data['lng'] ;
        $data['zoomLevel'] = 9;
        $data['urllocation']  = route('projects.nearby',['distance'=>$data['distance'],'latlng'=>$data['latlng']]);
  
        return response()->view('projects.map',compact('data'));
    }

    public function makeNearbyProjectsXML($result) {
        $content = view('projects.xml', compact('result'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
        
    }
}
