<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Campaign;
class CampaignSummaryExport implements FromView
{
    public $branches;
    public $campaign;

    public function __construct(Campaign $campaign, $branches)
    {
        $this->branches = $branches;
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
            'campaigns.summaryexport',  [
                'branches' => $this->branches,
                'campaign' => $this->campaign
            ]
        );
    }
}