<?php

namespace App\Jobs;

use \Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyBranch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $period)
    {
        $this->period = $period;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // first get all users - with role ('RVP')?
        // then get their branches - myteam -> my branches
        // then get their data ($this->period); ->map
    }
}
