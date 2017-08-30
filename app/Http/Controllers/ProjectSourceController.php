<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProjectSource;
use Carbon\Carbon;

use App\Http\Requests\ProjectSourceRequest;
class ProjectSourceController extends Controller
{
    public $projectsource;
    public function __construct(ProjectSource $projectsource){
        $this->projectsource = $projectsource;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $sources = $this->projectsource->with('projects')->get();
        return response()->view('projectsource.index',compact('sources'));
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
        //
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
}
