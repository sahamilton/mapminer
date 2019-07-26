<?php

namespace App\Jobs;

use Mail;
use Excel;
use App\Address;
use App\Company;
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
    
    public $company;
    public $period;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Company $company,Array $period)
    {
        $this->company = $company;
        $this->period = $period;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $companyname = str_replace(" ", "_", $this->company->companyname);
        $file = "/public/reports/".$this->company->companyname."_activityreport_". Carbon::now()->timestamp. ".xlsx";
        Excel::store(
            new AccountActivitiesExport($this->company, $this->period), $file
        );
        $this->company->load('managedBy', 'managedBy.userdetails');
       
        //$distribution = ['athompson4@trueblue.com'=>'Amy Thompson'];
       
        Mail::to([['email'=>$this->company->managedBy->userdetails->email, 'name'=>$this->company->managedBy->fullName()]])
                
                ->send(
                    new AccountActivitiesReport($file, $this->period, $this->company)
                );
            
            
            
      
        
    }
}
