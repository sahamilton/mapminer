<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

use App\Models\Exports\Reports\Campaign\CampaignSummaryExport;
use App\Models\Mail\SendCampaignSummaryReport;
use App\Models\Campaign;
use App\Models\Report;
use App\Models\User;
use App\Models\Job\ReportReadyJob;


class CampaignSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $campaign;
    public $file;
    public $report;
    public $period;
    public $distribution; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        
        $this->campaign = $campaign;
        $this->period = $this->campaign->period();
       // $this->report = Report::where('job', class_basename($this))->firstOrFail();
        $this->distribution = User::where('id', 1)->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->distribution as $recipient) {
           
            $this->user = $recipient;
            $this->file = $this->_makeFileName();
            
            (new CampaignSummaryExport($this->campaign))
                ->store($this->file, 'reports') ->chain(
                    [
                        new ReportReadyJob(
                            $recipient, 
                            $this->period, 
                            $this->file, 
                            $this->report
                        )
                    ]
                )->onQueue('reports');;
            
            
        }

    }

   
    private function _makeFileName()
    {
 
        return 
            strtolower(
                Str::slug(
                    $this->user->person->fullName()." ".
                    $this->campaign->title ." ". 
                    now()->format('Y-m-d'),
                    '_'
                )
            ). ".xlsx";
    }
}
