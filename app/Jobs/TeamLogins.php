<?php

namespace App\Jobs;

use App\Exports\TeamLoginsExport;
use App\Mail\TeamLoginsReport;
use App\Report;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class TeamLogins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $manager;
    public $period;

    /**
     * [__construct description].
     *
     * @param array $period [description]
     */
    public function __construct(array $period, array $manager)
    {
        $this->period = $period;
        $this->manager = $manager;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = '/public/reports/teamlogins'.$this->period['to']->timestamp.'.xlsx';

        Excel::store(new TeamLoginsExport($this->period, $this->manager), $file);

        $class = str_replace("App\Jobs\\", '', get_class($this));
        $report = Report::with('distribution')
            ->where('job', $class)
            ->firstOrFail();
        $distribution = $report->getDistribution();
        Mail::to($distribution)
            ->send(new TeamLoginsReport($file, $this->period));
    }
}
