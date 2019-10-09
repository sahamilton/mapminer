<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Address;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Exports\AccountActivitiesExport;
use App\Mail\AccountActivitiesReport;

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
    public function __construct(Collection $companies, Array $period)
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
        foreach ($this->companies as $company) {
            $companyname = str_replace(" ", "_", $company->companyname);
            $file = "/public/reports/".$company->companyname."_activityreport_". Carbon::now()->timestamp. ".xlsx";
            Excel::store(
                new AccountActivitiesExport($company, $this->period), $file
            );
            $company->load('managedBy', 'managedBy.userdetails');
           
            //$distribution = ['athompson4@trueblue.com'=>'Amy Thompson'];
           
            Mail::to([['email'=>$company->managedBy->userdetails->email, 'name'=>$company->managedBy->fullName()]])
                    
                    ->send(
                        new AccountActivitiesReport($file, $this->period, $company)
                    );    
        }  
    }
}
