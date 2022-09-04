<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Mail\EmailConfirmBranchAssignments;
use Illuminate\Database\Eloquent\Collection;
use Mail;
class ConfirmBranchAssignments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $managers;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $managers)
    {
        $this->managers = $managers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->managers as $manager) {

            $branches = $manager->myBranches($manager);
            Mail::to([$manager->fullEmail()])
                
                ->send(new EmailConfirmBranchAssignments($branches, $manager));  

        }
    }
}
