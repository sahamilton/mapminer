<?php

namespace App\Http\Controllers;
use App\Lead;
use App\Address;
use App\Branch;
use App\BranchLead;
use Illuminate\Http\Request;

class BranchLeadController extends Controller
{
    public $lead;
    public $branch;
    public $address;
    public $branchlead;
    public function __construct(Lead $lead, BranchLead $branchlead,Branch $branch, Address $address){
        $this->lead = $lead;
        $this->address = $address;
        $this->branch = $branch;
        $this->$branchlead = $branchlead;;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->assign();

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
     * @param  \App\BranchLead  $branchLead
     * @return \Illuminate\Http\Response
     */
    public function show(BranchLead $branchLead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BranchLead  $branchLead
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchLead $branchLead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BranchLead  $branchLead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchLead $branchLead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BranchLead  $branchLead
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchLead $branchLead)
    {
        //
    }
    public function assign(){
        $leads = $this->address->where('state','=','CA')
        ->where('zip','like',"94%")
        ->where('addressable_type','=','lead')
        ->get();
       
        foreach ($leads as $lead){
            if($branch = $this->branch->nearby($lead,'25',1)->get()){
            
                $data = ['branch_id'=>$branch->first()->id, 'address_id'=>$lead->id];
               
                BranchLead::create($data);
            }         
            
        } 
    }
}
