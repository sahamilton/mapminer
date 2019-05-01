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
	public function __construct(Company $company, array $period)
    {
    	$this->period = $period;
    	$this->company = $company;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $results = Address::where('company_id','=',$this->company->id)
                ->whereHas('activities',function($q){
                    $q->whereBetween('activity_date',[$this->period['from'],$this->period['to']]);
                })
                ->with('activities','activities.type')
                ->get();
        $period = $this->period;
        $company = $this->company;
        return view('reports.accountactivityreport',compact('results','period','company'));
    }

}
