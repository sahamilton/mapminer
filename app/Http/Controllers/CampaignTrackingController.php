<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;
use App\Address;
use App\Branch;
use App\Campaign;
use App\Opportunity;
class CampaignTrackingController extends Controller
{
    public $activity;
    public $address;
    public $branch;
    public $campaign;
    public $opportunity;

    public function __construct(
        Activity $activity,
        Address $address,
        Branch $branch,
        Campaign $campaign,
        Opportunity $opportunity
    ) {
        $this->activity = $activity;
        $this->address = $address;
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->opportunity = $opportunity;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show(Campaign $campaign)
    {
        $campaign->load('companies', 'branches');
        
        $branch_ids = $campaign->branches->pluck('id')->toArray();
        $period['from'] = $campaign->datefrom;
        $period['to'] =$campaign->dateto;
        $branches = $this->branch->whereIn('id', $branch_ids)->summaryCampaignStats($campaign)->get();
   
        return response()->view('campaigns.summary', compact('campaign', 'branches'));
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
