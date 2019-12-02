<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;
use App\Address;
use App\Branch;
use App\Campaign;
use App\Opportunity;
use Excel;
use App\Exports\CampaignSummaryExport;
class CampaignTrackingController extends Controller
{
    public $activity;
    public $address;
    public $branch;
    public $campaign;
    public $opportunity;
    /**
     * [__construct description]
     * 
     * @param Activity    $activity    [description]
     * @param Address     $address     [description]
     * @param Branch      $branch      [description]
     * @param Campaign    $campaign    [description]
     * @param Opportunity $opportunity [description]
     */
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
     * [show description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function show(Campaign $campaign)
    {
        dd($campaign);
        $campaign->load('companies', 'branches');
        $branches = $this->_getBranchesInCampaign($campaign);
        $team = $this->_getCampaignBranchTeam($campaign);
        $campaigns = $this->campaign->current()->get();
        return response()->view('campaigns.summary', compact('campaign', 'branches', 'team', 'campaigns'));
    }

    /**
     * [export description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function export(Campaign $campaign)
    {
        $campaign->load('companies', 'branches');
        $branches = $this->_getBranchesInCampaign($campaign);
       
        return Excel::download(new CampaignSummaryExport($campaign, $branches), $campaign->title.time().'Export.csv');

    }
    /**
     * [_getBranchesInCampaign description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getBranchesInCampaign(Campaign $campaign)
    {
        $branch_ids = $campaign->branches->pluck('id')->toArray();
        $company_ids = $campaign->companies->pluck('id')->toArray();
        return $this->branch->whereIn('id', $branch_ids)
            ->whereHas(
                'locations', function ($q) use ($company_ids) {
                    $q->whereIn('company_id', $company_ids);
                }
            )->summaryCampaignStats($campaign)->get();
    }
    /**
     * [_getCampaignBranchTeam description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getCampaignBranchTeam(Campaign $campaign)
    {
        $servicelines = $campaign->getServicelines();
        return $this->campaign->getSalesTeamFromManager($campaign->manager_id, $servicelines);
    }

}
