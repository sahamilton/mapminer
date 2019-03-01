<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BranchActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        
       if(!  $myBranches = $this->person->myBranches()){
        return redirect()->back()->withError('You are not assigned to any branches');
       }
       
        $branch = array_keys($myBranches);

        $data = $this->getBranchActivities([reset($branch)]);
        
        $title= $data['branches']->first()->branchname . " activities";
         return response()->view('activities.index', compact('activities', 'data'));        return response()->view('activities.index', compact('data', 'myBranches','title'));
        // how to get the distance for each branch
        // get my branches
        // get addresses that are leads that are assigned to a branch
        //
    }

    public function branchActivities(Request $request, Branch $branch){

        if (request()->has('branch')) {
            $branch = request('branch');
        } else {
           $branch = $branch->id;
        }
        $myBranches = $this->person->myBranches();
       
        if(! ( $myBranches)  or ! in_array($branch,array_keys($myBranches))){
            return redirect()->back()->withError('You are not assigned to any branches');
       }
       
         
        $data = $this->getBranchctivities([$branch]);
       
        $title= $data['branches']->first()->branchname . " activities";
        return response()->view('activities.index', compact('data', 'myBranches','title'));
    }

    private function getBranchAcytivities(Array $branch){
        $data['activities'] = 

        $data['branches'] = $this->getBranches($branch);
        return $data;
    }

     private function getBranches(Array $branches)
       {
        return  $this->branch->with('manager')
            ->whereIn('id', $branches)
            ->get();
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
}
