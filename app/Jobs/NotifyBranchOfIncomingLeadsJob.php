<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Branch;

class NotifyBranchOfIncomingLeadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $branchfrom;
    public $branchto;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Branch $branchfrom, Branch $branchto)
    {
        $this->branchfrom = $branchfrom;
        $this->branchto = $branchto;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
