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
       
        
       if (!  $myBranches = $this->person->myBranches()) {
        return redirect()->back()->withError('You are not assigned to any branches');
       }
       
        $branch = array_keys($myBranches);

        $data = $this->getBranchActivities([reset($branch)]);
        
        $title= $data['branches']->first()->branchname . " activities";
         return response()->view('activities.index', compact('activities', 'data')); 
        // how to get the distance for each branch
        // get my branches
        // get addresses that are leads that are assigned to a branch
        //
    }
    /**
     * [branchActivities description]
     * 
     * @param Request $request [description]
     * @param Branch  $branch  [description]
     * 
     * @return [type]           [description]
     */
    public function branchActivities(Request $request, Branch $branch)
    {
       
        if (request()->has('branch')) {
            $branch = request('branch');
        } else {
            $branch = $branch->id;
        }
        $myBranches = $this->person->myBranches();
       
        if (! ( $myBranches)  or ! in_array($branch, array_keys($myBranches))) {
            return redirect()->back()->withError('You are not assigned to any branches');
        }
       
         
        $data = $this->getBranchActivities([$branch]);
       
        $title= $data['branches']->first()->branchname . " activities";

        return response()->view('activities.index', compact('data', 'myBranches', 'title'));
    }
    /**
     * [getBranchActivities description]
     * @param  Array  $branch [description]
     * @return [type]         [description]
     */
    private function getBranchActivities(Array $branch) {
        $data['activities'] = $this->getUpcomingActivities($branch);
        $data['calendar'] = $this->getUpcomingCalendar($data['activities']);

        $data['branches'] = $this->getBranches($branch);
        return $data;
    }
    /**
     * [getBranches description]
     * @param  Array  $branches [description]
     * @return [type]           [description]
     */
     private function getBranches(Array $branches)
       {
        return  $this->branch->with('manager')
            ->whereIn('id', $branches)
            ->get();
       }
    /**
     * [getUpcomingCalendar description]
     * @param  [type] $activities [description]
     * @return [type]             [description]
     */
    private function getUpcomingCalendar($activities)
    {
        
        return \Calendar::addEvents($activities);
    }

    /**
     * [Return open activities for branch]
     * @param  Array  $myBranches [description]
     * @return [Collection]     [description]
     */
    private function getUpcomingActivities(Array $myBranches)
    {
            // should rename to open activities
           $users =  $this->person->myBranchTeam($myBranches);

           return $this->activity->whereIn('user_id',$users)
           ->where('completed','<>',1)->get();

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
