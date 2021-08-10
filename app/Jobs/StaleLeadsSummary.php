<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Excel;
use App\Exports\Reports\Branch\StaleLeadsSummaryExport;

class StaleLeadsSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $branches;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $branches)
    {
        $this->branches = $branches;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::store(new StaleLeadsSummaryExport($this->branches),  $this->file, 'reports');
        
    }
}
