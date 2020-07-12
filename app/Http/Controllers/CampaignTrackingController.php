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
    public $fields = [
                    "supplied_leads",
                    "offered_leads",
                    "worked_leads",
                    "rejected_leads",
                    "touched_leads",
                    "new_opportunities",
                    "won_opportunities",
                    "opportunities_open",
                    "won_value",
                    "open_value",
                ];
    public $openfields = [
                    
                    "campaign_leads",
     
                    "touched_leads",
                    "new_opportunities",
                    "won_opportunities",
                    "open_opportunities",
                    "won_value",
                    "open_value",
                ];
    /**
     * [__construct description]
     * 
     * @param Activity    $activity    [description]
     * @param Address     $address     [description]
     * @param Branch      $branch      [description]
     * @param Campaign    $campaign    [description]
     * @param Company     $company     [description]
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
        //$branches = $campaign->branches->pluck('id')->toArray();
        $team = $this->_getCampaignBranchTeam($campaign);
        
      
        $campaigns = $this->campaign->current($branches->pluck('id')->toArray())->get();
        if($campaign->type=== 'open') {
            $fields =  $this->openfields;
        }else{
            $fields =  $this->fields;
        }
     
        return response()->view('campaigns.summary', compact('campaign', 'branches', 'team', 'campaigns', 'fields'));
    }
    /**
     * [company description]
     * 
     * @param Request  $request  [description]
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    public function company(Request $request, Campaign $campaign)
    {
        
        return $this->summaryByCompany($campaign, request('manager_id'));
    }
    /**
     * [summaryByCompany description]
     * 
     * @param Campaign $campaign [description]
     * @param [type]   $manager  [description]
     * 
     * @return [type]             [description]
     */
    public function summaryByCompany(Campaign $campaign, $manager=null)
    {
        $campaign->load('companies', 'branches');
        $campaigns = $this->campaign->active()->get();
        $companies = $campaign->companies->pluck('id')->toarray();
        $branches =  $campaign->branches->pluck('id')->toArray();
        $period = $this->_getCampaignPeriod($campaign);
        if (! $manager) {
            $manager = $campaign->manager_id;
        }
        $fields = $this->fields;
    
        $team = $this->_getCampaignBranchTeam($campaign, $manager);
        $companies = $this->company->whereIn('id', $companies)->summaryStats($period, $branches)->get();
        return response()->view('campaigns.companysummary', compact('companies', 'campaigns', 'campaign', 'team', 'fields'));
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
        $fields = $this->fields;
        //dd($branches);
        //$branches= $this->_getAllBranchesInCampaign($campaign);
        //dd($branches);
        return Excel::download(new CampaignSummaryExport($campaign, $branches, $fields), $campaign->title.time().'Export.csv');

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
        $companies = $campaign->companies()->pluck('id')->toArray();
        $branches = $campaign->branches()->pluck('id')->toArray();
        $period = $this->_getCampaignPeriod($campaign);
        $companies = $this->company->whereIn('id', $companies)->summaryStats($period, $branches)->get();
        $fields = $this->fields;
        return Excel::download(new CampaignCompanyExport($campaign, $companies, $fields), $campaign->title.time().'CompanyExport.csv');

    }

    /**
     * [detailByCompany description]
     * 
     * @param Campaign $campaign [description]
     * @param Company  $company  [description]
     * 
     * @return [type]             [description]
     */
    public function detailByCompany(Campaign $campaign, Company $company)
    {
      
        $period = $this->_getCampaignPeriod($campaign);
        $branches = $campaign->branches()->pluck('id')->toArray();
        //$company = $this->company->companyDetail($period, $branches)->findOrFail($company->id);
        $branches = $this->branch->whereIn('id', $branches)->summaryCampaignStats($campaign, [$company->id])->get();
        $fields =$this->fields;
        return response()->view('campaigns.companydetail', compact('period', 'campaign', 'company', 'branches', 'fields'));
       
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
        if ($campaign->type === 'open') {
            return $this->branch->whereIn('id', $branch_ids)
            ->summaryOpenCampaignStats($campaign)->get();
        }
        return $this->branch->whereIn('id', $branch_ids)
            ->summaryCampaignStats($campaign)->get();
    }
    /**
     * [_getAllBranchesInCampaign description]
     * 
     * @param Campaign $campaign [description]
     * 
     * @return [type]             [description]
     */
    private function _getAllBranchesInCampaign(Campaign $campaign)
    {
        // this doesnt make much sense!
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
     * @param [type]   $manager  [description]
     * 
     * @return [type]             [description]
     */
    private function _getCampaignBranchTeam(Campaign $campaign, $manager=null)
    {
        $servicelines = $campaign->getServicelines();
        if (! $manager) {
            $manager= $campaign->manager_id;
        }
        
        return $this->campaign->getSalesTeamFromManager($manager, $servicelines);
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
