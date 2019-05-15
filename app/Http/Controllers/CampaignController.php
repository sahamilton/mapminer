<?php

namespace App\Http\Controllers;

use App\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    
    public $campaign;

    /**
     * [__construct description]
     * 
     * @param Campaign $campaign [description]
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = $this->campaign->with('participants', 'respondents', 'author')
            ->get();
        return response()->view('campaigns.index', compact('campaigns'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Campaign $campaign [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        $campaign = $this->campaign->with('participants', 'author')
            ->findOrFail($campaign->id);
        return response()->view('campaigns.show', compact('campaign'));
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Campaign $campaign [description]
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->back()->withMessage("Campaign Deleted");
    }
}
