<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Exports\Reports\Campaign\CampaignSummaryExport;
use App\Mail\SendCampaignSummaryReport;
use App\Campaign;
use App\Report;
use App\User;
use App\Job\ReportReadyJob;


class CampaignSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $campaign;
    public $file;
    public $report;
    public $period; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->period = $this->campaign->period();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->report = Report::first();
        $recipient = User::findOrFail(1);
        $this->file = 'summary_report_'.now()->timestamp.'.xlsx';
        (new CampaignSummaryExport($this->campaign))
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

    /*
    (new $export($this->period, $branches))
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
     */
    
}
