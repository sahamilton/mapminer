<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;
use App\Address;
use App\Branch;
use App\Campaign;
USE App\Company;
use App\Opportunity;
use Excel;
use App\Exports\CampaignSummaryExport;
use App\Exports\CampaignCompanyExport;
class CampaignTrackingController extends Controller
{
    public $activity;
    public $address;
    public $branch;
    public $campaign;
    PUBLIC $company;
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
        Company $company,
        Opportunity $opportunity
    ) {
        $this->activity = $activity;
        $this->address = $address;
        $this->branch = $branch;
        $this->campaign = $campaign;
        $this->company = $company;
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
      
        $campaign->load('companies', 'branches');
        $branches = $this->_getBranchesInCampaign($campaign);
        $team = $this->_getCampaignBranchTeam($campaign);
        $campaigns = $this->campaign->current()->get();
       
        return response()->view('campaigns.summary', compact('campaign', 'branches', 'team', 'campaigns'));
    }

    /**
     * [summaryByCompany description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function summaryByCompany(Campaign $campaign)
    {
        $campaign->load('companies', 'branches');
        $campaigns = $this->campaign->active()->get();
        $companies = $campaign->companies->pluck('id')->toarray();
        $branches =  $campaign->branches->pluck('id')->toArray();
        $period = $this->_getCampaignPeriod($campaign);
        $companies = $this->company->whereIn('id', $companies)->summaryStats($period, $branches)->get();
        return response()->view('campaigns.companysummary', compact('companies', 'campaigns', 'campaign'));
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
        //dd($branches);
        //$branches= $this->_getAllBranchesInCampaign($campaign);
        //dd($branches);
        return Excel::download(new CampaignSummaryExport($campaign, $branches), $campaign->title.time().'Export.csv');

    }

    /**
     * [export description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function exportCompany(Campaign $campaign)
    {
        $companies = $campaign->companies()->pluck('id')->toarray();
        $period = $this->_getCampaignPeriod($campaign);
        $companies = $this->company->whereIn('id', $companies)->summaryStats($period)->get();
        
        return Excel::download(new CampaignCompanyExport($campaign, $companies), $campaign->title.time().'CompanyExport.csv');

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

    private function _getAllBranchesInCampaign(Campaign $campaign)
    {
        $branch_ids = $this->branch->pluck('id')->toArray();
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

    /**
     * [_getCampaignPeriod description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getCampaignPeriod(Campaign $campaign)
    {
        $period['from'] = $campaign->datefrom;
        $period['to'] = $campaign->dateto;
        return $period;
    }
}
