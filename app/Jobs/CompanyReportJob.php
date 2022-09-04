<?php

namespace App\Jobs;

use Mail;
use App\Models\Report;
use App\Models\Person;
use App\Models\User;
use App\Models\Company;
use App\Models\Exports\ActivityOpportunityExport;

use Illuminate\Support\Str;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class CompanyReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $distribution;
    public $file;
    public $company;
    public $period;
    public $person;
    public $report; 
    public $user;
    
    public function __construct(
        Report $report, 
        Array $period = null, 
        $distribution = null, 
        $company = null
    ) {
        
        $this->period = $period;
        $this->report = $report;
        $this->company = Company::findOrFail($company);;   
        $this->distribution = $distribution;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $distribution = $this->_getDistribution();
       
        foreach ($distribution as $recipient) {
            $this->user = $recipient;
            $this->file = $this->_makeFileName();
           
            $export = $this->_getExportClass();
         
            (new $export($this->report, $this->company, $this->period))
                ->store($this->file, 'reports')
                ->chain(
                    [
                        new ReportReadyJob(
                            $recipient, 
                            $this->period, 
                            $this->file, 
                            $this->report
                        )
                    ]
                )->onQueue('reports');
            
        }
    }

    private function _makeFileName()
    {
        if (! is_a($this->user, 'App\User')) {
            $this->user = User::with('person')->first();          
        }

        return 
                strtolower(
                    Str::slug(
                        $this->company->companyname." ".
                        $this->report->report." ".
                        $this->report->filename ." ". 
                        $this->period['from']->format('Y_m_d'), 
                        '_'
                    )
                ). ".xlsx";  
        
    }

    private function _getReportBranches($recipient)
    {
        if ($this->manager) {

            return Person::findOrFail($this->manager)->getMyBranches();
        }
        return $recipient->person->getMyBranches();
    }

    private function _getExportClass()
    {
        
        return "\App\Exports\Reports\Company\\". $this->report->export;   

    }
    /**
     * [_getDistribution description]
     * 
     * @return Collection [description]
     */
    private function _getDistribution()
    {
        if ($this->distribution) {
            return $this->distribution;
        } elseif (! $this->report->distribution->count()) {
            if (auth()->user()) {
                return User::where('id', auth()->user()->id)->get();
            } else {
                return User::where('id', 1)->get();
            }
            
        }
        return $this->report->distribution;
    }  
}
