<?php

namespace App\Http\Controllers;
use App\Person;
use App\Lead;
use App\LeadStatus;
use Illuminate\Http\Request;

class SalesLeadsController extends Controller
{
    public $saleslead;
    public $person;
    public $leadstatus;
    public function __construct(Lead $saleslead, Person $person, LeadStatus $status){

        $this->salesleads = $saleslead;
        $this-> person = $person;
        $this->leadstatus = $status;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = $this->leadstatus->pluck('status','id')->toArray();
        $leads = $this->person->with('salesleads','salesleads.vertical','salesleads.salesteam')
        
        ->where('user_id','=',auth()->user()->id)
        ->firstOrFail();
        return response()->view('salesleads.index',compact('leads','statuses'));
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
        //
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

    public function accept($id){
     
      $lead = $this->salesleads->with('salesteam')->find($id);
   
      $salesteam = $lead->salesteam->pluck('id');
      foreach ($salesteam as $id){
        if($id == auth()->user()->person->id){
            $lead->salesteam()->updateExistingPivot($id,['status_id'=>2]);
        }else{
            $lead->salesteam()->updateExistingPivot($id,['status_id'=>3]);
        }
      }
      
       return redirect()->route('salesleads.index');
    }

    public function decline($id){
     
      $lead = $this->salesleads->with('salesteam')->find($id);

      $lead->salesteam()->updateExistingPivot(auth()->user()->person->id,['status_id'=>4]);

       return redirect()->route('salesleads.index');
    }
}
