<?php

namespace App\Exports;

use App\Campaign;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CampaignSummaryExport implements FromView
{
    public $branches;
    public $campaign;
    public $fields;

    public function __construct(Campaign $campaign, $branches, array $fields)
    {
        $this->branches = $branches;
        $this->campaign = $campaign;
        $this->fields = $fields;
    }

    /**
     * [view description].
     *
     * @return [type] [description]
     */
    public function view(): View
    {
        return view(
            'campaigns.summaryexport', [
            'branches' => $this->branches,
            'campaign' => $this->campaign,
            'fields'=>$this->fields,
            ]
        );
    }
}
