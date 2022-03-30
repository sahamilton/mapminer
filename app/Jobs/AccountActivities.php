<?php

namespace App\Jobs;

use App\Address;
use App\Company;
use App\Exports\AccountActivitiesExport;
use App\Mail\AccountActivitiesReport;
use Carbon\Carbon;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class AccountActivities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $companies;
    public $period;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $period, Company $companies )
    {
        $this->companies = $companies;
        $this->period = $period;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $company = $this->companies;
        $companyname = str_replace(' ', '_', $company->companyname);
        $this->file = $company->companyname.'_activityreport_'.Carbon::now()->timestamp.'.xlsx';
        Excel::store(new AccountActivitiesExport($company, $this->period), $file, 'reports');
        $company->load('managedBy', 'managedBy.userdetails');

        //$distribution = ['athompson4@trueblue.com'=>'Amy Thompson'];

        Mail::to([['email'=>$company->managedBy->userdetails->email, 'name'=>$company->managedBy->fullName()]])

                    ->send(
                        new AccountActivitiesReport($file, $this->period, $company)
                    );
    }
}
