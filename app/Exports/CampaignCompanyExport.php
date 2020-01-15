<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Campaign;
class CampaignCompanyExport implements FromView
{
    public $companies;
    public $campaign;

    public function __construct(Campaign $campaign, $companies)
    {
        $this->companies = $companies;
        $this->campaign = $campaign;
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        return view(
            'campaigns.companyexport',  [
                'companies' => $this->companies,
                'campaign' => $this->campaign,
                'fields' =>  [
                    "offered_leads",
                    "worked_leads",
                    "rejected_leads",
                    "new_opportunities",
                    "won_opportunities",
                    "opportunities_open",
                    "won_value",
                    "open_value" 
                    ] 
   
            ]
        );
    }
}