<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProjectSource;
use Carbon\Carbon;
use App\Project;
use App\Http\Requests\ProjectSourceRequest;
class ProjectSourceController extends Controller
{
    public $projectsource;
    public $project;
    public function __construct(ProjectSource $projectsource,Project $project){
        $this->projectsource = $projectsource;
        $this->project = $project;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $sources = $this->projectsource->get();

        $stats = $this->getStats($sources);

        return response()->view('projectsource.index',compact('sources','stats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('projectsource.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectSourceRequest $request)
    {
        $data = $request->all();
        $data['datefrom'] = Carbon::createFromFormat('d/m/Y', $request->get('datefrom'));
        $data['dateto'] = Carbon::createFromFormat('d/m/Y', $request->get('dateto'));

        if($this->projectsource->create($data)){
            return redirect()->route('projectsource.index')
            ->with('sucess','Project Source Created');
        }
        return redirect()->route('projectsource.index')
            ->with('error','Project Source Not Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $projectsource = $this->projectsource->with('projects')->findOrFail($id);
        return response()->view('projectsource.show',compact('projectsource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $projectsource = $this->projectsource->with('projects')->findOrFail($id);
        return response()->view('projectsource.edit',compact('projectsource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectSourceRequest $request, $id)
    {
        $data = $request->all();
        $data['datefrom'] = Carbon::createFromFormat('m/d/Y', $request->get('datefrom'));
        $data['dateto'] = Carbon::createFromFormat('m/d/Y', $request->get('dateto'));
        $projectsource = $this->projectsource->with('projects')->findOrFail($id);
        if($projectsource->update($data)){
            return redirect()->route('projectsource.index')
            ->with('sucess','Project Source Updated');
        }
        return redirect()->route('projectsource.index')
            ->with('error','Project Source Not Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->projectsource->destroy($id)){
             return redirect()->route('projectsource.index')
            ->with('success','Project Source deleted');
        }
         return redirect()->route('projectsource.index')
            ->with('error','Unable to delete Project Source');
    }

    private function getStats($sources){
        foreach ($sources as $source){
            $stats[$source->id]['count'] = $source->projects()->count();
            $owned = $source->projects()->has('owner')->with('owner')->get();
            $stats[$source->id]['statuses']= $this->getStatuses($owned);
            $stats[$source->id]['ranking']= $this->getRanking($owned);
            $stats[$source->id]['owned'] = $owned->count();

        }
    return $stats;
        
    }
    private function getRanking($owned){
        $data['ranking']=0;
        $count=0;
        foreach ($owned as $project){
            if(isset($project->owner->first()->pivot->ranking)){
                $count++;
                $data['ranking']= $data['ranking'] + $project->owner->first()->pivot->ranking;
            }
        }
        if($count>0){
            return $data['ranking'] / $count;
        }
        return $data['ranking'];
        
    }

    private function getStatuses($owned){
        foreach ($this->project->statuses as $status){
            $data[$status] = 0;
        }
        
        foreach ($owned as $project){

            $data[$project->owner->first()->pivot->status]++;
        }
        return $data;
    }
}