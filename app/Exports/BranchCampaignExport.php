<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Role;
use App\Branch;
use App\Campaign;

class BranchCampaginExport implements FromView
{   
    public $branch;
    /**
     * [__construct description]
     * 
     * @param Array|null $branch [description]
     */
    public function __construct(Campaign $campaign, Array $branch=null)
    {
        $this->branch = $branch;
        $this->campaign = $campaign;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $result = Branch::with('address', 'manager');
        if ($this->branch) {
            $result = $result->campaignDetail($campaign)
                ->whereIn('id', $this->branch);

        } else {
            $result = $result->campaignStats($campaign)
                ->whereIn('id', $campaign->branches->pluck('id')->toArray());
        }
        $result->get();
        return view('campaigns.export', compact('result'));
    }
}
