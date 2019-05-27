<?php

namespace App\Exports;
use App\Address;
use App\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class AccountActivitiesExport implements FromView
{
    public $period;
    public $company;
    /**
     * [__construct description]
     * @param Company $company [description]
     * @param array   $period  [description]
     */
    public function __construct(Company $company, array $period)
    {
        $this->period = $period;
        $this->company = $company;
    }

    /**
     * [view description]
     * 
     * @return [type] [description]
     */
    public function view(): View
    {
        $results = Address::where('company_id', $this->company->id)
                ->with(
                    ['activities'=>function ($q) {
                        $q->where('completed', '1')
                            ->whereBetween('activity_date', [$this->period['from'],$this->period['to']]);
                    }]
                )
                ->with('activities.type')
                ->get();
        $period = $this->period;
        $company = $this->company;
        return view('reports.accountactivityreport', compact('results', 'period', 'company'));
    }

}