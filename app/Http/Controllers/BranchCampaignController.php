<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\Branch;
use App\Person;

class BranchCampaignController extends Controller
{
    public $branch;
    public $campaign;
    public $person;
    
    /**
     * [__construct description]
     * 
     * @param Branch        $branch   [description]
     * @param Salesactivity $campaign [description]
     */
    public function __construct(
        Branch $branch, 
        Campaign $campaign,
        Person $person
    ) {
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->person = $person;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$this->myBranches = $this->branch->getBranches();
        /**  
        Testing code [description] 
        */
        $myBranches = $this->branch->whereId(1276)->get();

        /**
         * End test
         */
        
        $campaign = $this->campaign->current(array_keys($myBranches->pluck('branchname', 'id')->toArray()))->get();

        if (! $campaign->count()) {
            return redirect()->back()->withMessage('there are no current sales campaigns for your branches');
        }
        
        $campaign = $campaign->first();
        $branch_ids = array_keys($myBranches->pluck('branchname', 'id')->toArray());
        $branches = $this->branch
            ->whereIn('id', $branch_ids)
            ->summaryCampaignStats($campaign->first())
            ->get();
       
        //$locations = $this->_getLocationsForMyBranches($campaign, $myBranches);
        return response()->view('campaigns.summary', compact('campaign', 'branches'));


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

    private function _getLocationsForMyBranches($campaigns, $branches)
    {
        
        $company_ids = $this->_getCampaignCompanyIDs($campaigns);
        
        dd($leads);
        
       
    }

    private function _getCampaignCompanyIDs($campaigns)
    {
        $companies = $campaigns->map(
            function ($campaign) {
                return $campaign->companies->pluck('id')->toArray();

            }
        );
        return $companies->flatten()->toArray();
    }
}
