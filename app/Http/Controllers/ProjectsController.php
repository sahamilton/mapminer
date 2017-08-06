<?php

namespace App\Http\Controllers;

use Excel;
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
       \Session::put('type','projects');

       if(\Session::has('geo')){
      
        return redirect()->route('findme');
       }

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
        $statuses = $this->project->statuses;
        $project = $this->project->with('companies','owner')->findOrFail($id);
        return response()->view('projects.show',compact('project','statuses'));
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

    public function claimProject($id){
        $project = $this->project->findOrFail($id);
        $project->owner()->attach(auth()->user()->person()->first()->id,['status'=>'Claimed']);
        return redirect()->route('projects.show',$id);

    }
    public function changeStatus (Request $request){
       $project = $this->project->findOrFail($request->get('project_id'));
       if (! $request->has('status')){
            $project->owner()->detach(auth()->user()->person()->first()->id);
       }else{
        
        $project->owner()->updateExistingPivot(auth()->user()->person()->first()->id,['status'=>$request->get('status')]);
        }
        return redirect()->route('projects.show',$request->get('project_id'));
    }

    public function myProjects(){

        $projects = $this->getMyProjects();
        return response()->view('projects.myprojects',compact('projects'));
    }

    public function exportMyProjects(){
            Excel::create('Projects',function($excel){
            $excel->sheet('Watching',function($sheet) {
                $projects = $this->getMyProjects();           
                $sheet->loadView('projects.export',compact('projects'));
            });
        })->download('csv');

        return response()->return();


    }

    public function exportowned(){
            Excel::create('Projects',function($excel){
            $excel->sheet('Watching',function($sheet) {
                $projects = $this->getOwnedProjects();           
                $sheet->loadView('projects.exportowned',compact('projects'));
            });
        })->download('csv');

        return response()->return();


    }
    public function statuses(){
            $projects = $this->getOwnedProjects();

            return response()->view('projects.owned',compact('projects'));
    }

    private function getOwnedProjects(){
        return $this->project->with('owner')
            ->whereHas('owner')->get();
    }
    private function getMyProjects(){
       return $this->project->with('owner','companies')
        ->whereHas('owner',function($q){
            $q->where('person_id','=',auth()->user()->person()->first()->id);
        })->get();
    }

    


}
