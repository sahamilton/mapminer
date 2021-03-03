<?php

namespace App\Jobs;

use App\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignCampaignLeads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $branch_id;
    public $addresses;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($branch_id, $addresses)
    {
        $this->branch_id = $branch_id;
        $this->addresses = $addresses;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $attach = [];

        foreach ($this->addresses as $address) {
            $attach[$address] = ['status_id'=>1];
        }
        Branch::find($this->branch_id)->leads()->attach($attach);
    }
}
