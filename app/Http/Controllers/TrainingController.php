<?php

namespace App\Http\Controllers;

use App\Training;
use Illuminate\Http\Request;
use App\Role;
use App\Http\Requests\TrainingFormRequest;
class TrainingController extends BaseController
{
    protected $training;

    public function __construct(Training $training){
        $this->training = $training;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainings = $this->training->all();
        return response()->view('training.index',compact('trainings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','id')->toArray();

        return response()->view('training.create',compact('roles','servicelines','verticals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingFormRequest $request)
    {
        $data = $request->all();
        $data = $this->setDates($data);

        if($request->has('noexpiration')){
            $data['dateto']=null;
        }
        if($training = $this->training->create($data)){
            //currently not using service line specific training
           // $training->serviceline()->attach($request->get('serviceline'));
            if($request->filled('vertical')){
                $training->relatedIndustries()->attach($request->get('vertical'));
            }
            if($request->filled('role')){
                $training->relatedRoles()->attach($request->get('role'));
            }
        }
        
        return redirect()->route('training.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function show(Training $training)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function edit(Training $training)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function update(TrainingFormRequest $request, Training $training)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function destroy(Training $training)
    {
        //
    }
}
