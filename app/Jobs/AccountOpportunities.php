<?php

namespace App\Jobs;

use App\Address;
use App\Company;
use App\Exports\AccountOpportunitiesExport;
use App\Mail\AccountOpportunitiesReport;
use Carbon\Carbon;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class AccountOpportunities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $company;
    public $period;
    public $distribution;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Company $company, array $period, array $distribution)
    {
        $this->company = $company;
        $this->period = $period;
        $this->distribution = $distribution;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $companyname = str_replace(' ', '_', $this->company->companyname);
        $file = '/public/reports/'.$this->company->companyname.'_opportunityreport_'.Carbon::now()->timestamp.'.xlsx';
        Excel::store(new AccountOpportunitiesExport($this->company, $this->period), $file);
        $this->company->load('managedBy');
        $manageremail = $this->company->managedBy->userdetails()->first()->email;
        // get disty list
        Mail::to($this->distribution['to'])
            ->cc(
                isset($this->distribution['cc']) ? $this->distribution['cc'] : config('mapminer.system_contact')
            )
            ->send(
                new AccountOpportunitiesReport($file, $this->period, $this->company)
            );
    }
}
