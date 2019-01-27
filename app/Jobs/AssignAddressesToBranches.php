<?php

namespace App\Jobs;

use App\Branch;
use App\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignAddressesToBranches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $distance = 25;
    public $limit = 5;
    public $branch;
    public $address;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($branches,Address $address)
    {
        $this->branch = $branches;

       $this->address = $address;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        foreach ($this->branch as $branch)
            {
                 return $this->address->assignedToBranch()->attach($branch->id,['status_id'=>1]);
            }
    }
}
