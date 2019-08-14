<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Salesactivity;
use App\Branch;

class BranchCampaignController extends Controller
{
    public $branch;
    public $campaign;
    /**
     * [__construct description]
     * 
     * @param Branch        $branch   [description]
     * @param Salesactivity $campaign [description]
     */
    public function __construct(
        Branch $branch, 
        Salesactivity $campaign
    ) {
        $this->branch = $branch;
        $this->campaign = $campaign;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->myBranches = $this->_getBranches();
        $campaign = $this->campaign->currentActivities()->get();
        if ($campaign->count ==0) {
            return 'threre are no current sales campaigns';
        }
        $branchess = $campaign->map(
            function ($camp) { 
                return $camp->campaignBranches->pluck('id')->toArray();
            }
        );
        if (count($this->myBranches)>0) {
            $branch = array_keys($this->myBranches);
            return redirect()->route('dashboard.show', $branch[0]);
        } else {
            return redirect()->route('user.show', auth()->user()->id)
                ->withWarning("You are not assigned to any branches. You can assign yourself here or contact Sales Ops");
        }


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
