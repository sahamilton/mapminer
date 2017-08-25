<?php

namespace App\Http\Controllers;

use Excel;
use App\Branch;
use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends BaseController
{
    public $project;
    public $branch;

    public function __construct(Project $projects, Branch $branch){

        $this->project = $projects;
        $this->branch = $branch;
        parent::__construct($projects);
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
        $project = $this->project->with('companies','owner','relatedNotes')->findOrFail($id);
     
        $branches = $this->branch->findNearbyBranches($project->project_lat,$project->project_lng,100,$limit=5,$this->userServiceLines);
        
        return response()->view('projects.show',compact('project','statuses','branches'));
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
        $result = $this->project->findNearbyProjects($lat,$lng,$distance,$limit,$this->userServiceLines);
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
    public function ownedProjects($id){
        $projects = $this->project->whereHas('owner',function ($q) use($id) {
            $q->where('id','=',$id);
        })->with('owner')->get();;
        return response()->view('projects.ownedBy',compact('projects'));
    }
   

    public function projectStats(){

        $projects = $this->project->projectStats();
        $total = $this->project->projectcount();      
        $owned = count($this->getOwnedProjects());

        $projects = $this->createStats($projects); 
        $statuses = $this->project->statuses;
        return response()->view('projects.stats',compact('projects','statuses','total','owned'));

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

    
    private function createStats($projects){
        $person = null;
        foreach ($this->project->statuses as $status){
            $personProject['total']['status'][$status] = 0;
        }
        
        foreach ($projects as $project){
            if($project->id != $person){
                $person = $project->id;
                $personProject[$project->id]['name'] = $project->firstname . " " . $project->lastname;
                $personProject[$project->id]['id'] = $project->id;
            }
            $personProject[$project->id]['status'][$project->status] = $project->count;
            $personProject['total']['status'][$project->status] =$personProject['total']['status'][$project->status] + $project->count;
        }

        return $personProject;

    }
    private function getMyProjects(){
       return $this->project->with('owner','companies')
        ->whereHas('owner',function($q){
            $q->where('person_id','=',auth()->user()->person()->first()->id);
        })->get();
    }

    private function getOwnedProjects(){

        return $this->project->has('owner')->get();
        
    }

}
