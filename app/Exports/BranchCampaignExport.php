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
     * [view description]
     * 
     * @return View [description]
     */
    public function view(): View
    {
        $result = Branch::with('address', 'manager');
        // if a single branch then send detail
        if ($this->branch && ! $this->branch->count()) {
            $result = $result->campaignDetail($campaign)
                ->whereIn('id', $this->branch)
                ->get();
            return view('campaigns.exports.detail', compact('result'));
        } else {
            $result = $result->campaignStats($campaign)
                ->whereIn('id', $campaign->branches->pluck('id')->toArray())
                ->get();
            return view('campaigns.exports.summary', compact('result'));
        }
        
    }
}
