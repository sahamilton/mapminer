<?php

namespace App\Http\Controllers;

use App\Training;
use Illuminate\Http\Request;
use App\Role;
use App\SearchFilter;
use App\Serviceline;
use App\Http\Requests\TrainingFormRequest;
class TrainingController extends BaseController
{
    protected $training;
    public $userVerticals;
    public function __construct(Training $training){
        $this->training = $training;
        parent::__construct($training);

    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->hasRole('Admin')){
            $trainings = $this->training->with('relatedRoles','relatedIndustries','servicelines')->get();
            return response()->view('training.index',compact('trainings'));
        }else{
            return redirect()->route('mytraining');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','id')->toArray();
       
        $verticals = $this->getAllVerticals();
        $servicelines = $this->getAllServicelines();

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
            
            $training->servicelines()->attach($request->get('serviceline'));
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
    public function show($id){
        $training = $this->training->findOrFail($id);
        return response()->view('training.view',compact('training'));

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
     * @param  \Http\Requests\TrainingFormRequest  $request
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


    public function mytraining(){
       
        $training = $this->training->query();
        // find users servicelines
         $training->whereHas('servicelines', function ($q){
            $q->whereIn('id',$this->userServiceLines);
         });
        // find users industries
        if(count($this->userVerticals)>0){
                 $training->whereHas('relatedIndustries', function ($q){
                    $q->whereIn('id',$this->userVerticals);
                 });
             }
        // find users roles
         $training->whereHas('relatedRoles', function ($q){
            $q->whereIn('id',$this->userRoles);
         });
         $trainings = $training->get();

         return response()->view('training.mytrainings',compact('trainings'));

    }
    
}
