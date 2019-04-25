<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ReportsController extends Controller
{
    
    public $branch;
    public $period;
    public function __construct(Branch $branch){

        $this->branch = $branch;
        $this->period['from'] = Carbon::parse('01/01/2019');
        $this->period['to'] = Carbon::parse('03/11/2019');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = $this->getBranchActivity();
        $opportunities = $this->getBranchWonOpportunities();
        $data = $this->summarizeData($activities,$opportunities);
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

    private function summarizeData($branchactivities,$branchopportunities)
    {
        $data['activities'] = $this->summarizeActivities($branchactivities);
        $data['opportunities'] = $this->summarizeOpportunities($branchopportunities);
        
        $data = $this->consoliData($data);
    }

    private function consoliData($data)
    {
     
        return $data;
    }
   private function summarizeOpportunities($branchopportunities)
   {
    $data=[];
    foreach($branchopportunities as $branch){
        
        foreach ($branch->opportunities as $opportunity){
            
          $data[$branch->id][$opportunity->yearweek] = $opportunity->opportunities;
        }
    }
 
    return $data;
   }
   private function summarizeActivities($branchactivities)
   {
    
    foreach($branchactivities as $branch){
        foreach ($branch->activities as $activity){
            //dd(131,$branch->id,$activity->yearweek, $activity->activities);
            $data[$branch->id][$activity->yearweek]= $activity->activities;
        }
    }
   dd(135,$data);
    return $data;
   }
   /**
     * [getSummaryBranchData description]
     * @param  array  $branches [description]
     * @return [type]           [description]
     */
    private function getBranchWonOpportunities(){
       
        return $this->branch
              ->with(       
                      [
                        
                        'opportunities'=>function($query){
                  
                          $query->whereClosed(1)
                          ->whereBetween('actual_close',[$this->period['from'],$this->period['to']])
                          ->sevenDayCount();
                        }
                    ])
             
              ->get();
              
    }



   /**
     * [getSummaryBranchData description]
     * @param  array  $branches [description]
     * @return [type]           [description]
     */
    private function getBranchActivity(){
      
        return $this->branch
              ->with(       
                      [ 'activities'=>function($query){
                            $query->whereBetween('activity_date',[$this->period['from'],$this->period['to']])
                            ->where('activitytype_id','=','4')
                            ->where('completed','=',1)
                            ->sevenDayCount();
                        }
                    ])
           // ->where('id','=','8029')
            ->get(); 

    }

    
}
