<?php

namespace App\Http\Controllers;

use App\Dashboard;
use App\Person;
use App\Branch;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $dashboard;
    public $person;
    public $branch;

    public function __construct(Dashboard $dashboard,Person $person,Branch $branch)
    {
        $this->dashboard = $dashboard;
        $this->person = $person;
        $this->branch = $branch;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $branchCount = $this->dashboard->checkBranchCount();
        if($branchCount > 1){
            return redirect()->route('mgrdashboard.index');
        }else{
            return redirect()->route('branchdashboard.index');
        }
        


    }

    public function show($branch)
    {
       
        $branch = $this->branch->with('manager')->findOrFail($branch);
        return redirect()->route('branchdashboard.show',$branch->id);
        
    }

   public function select (Request $request)
   {
        
        $this->manager = $this->person->with('manages')->findOrFail(request('manager'));
     
        $branchCount = $this->dashboard->checkBranchCount($this->manager);
        
       if($branchCount > 1){
            
            return redirect()->route('manager.dashboard',$this->manager->id);
        }elseif($branchCount==1 && count($this->manager->manages) >0){
        //get my branch
            return redirect()->route('branchdashboard.show',$this->manager->manages->first()->id);
        }else{
            return redirect()->back()->withWarning($this->manager->fullName() . 'is not associated with any branches');
        }
    
   }
}
