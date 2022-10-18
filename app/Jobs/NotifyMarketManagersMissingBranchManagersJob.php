<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Oracle;
use App\Mail\NotifyMarketManagersMissingBranchManagersMail;
use Mail;

class NotifyMarketManagersMissingBranchManagersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $managers = Oracle::has('mapminerUser')
              ->whereHas(
                'mapminerRole', function ($q) {
                    $q->where('role_id', '3');
                }
            )
            ->whereHas(
                'teamMembers', function ($q) {
                    $q->doesntHave('mapminerUser')
                      ->where('job_code', 103);
                }
            )
            ->with(
                [
                    'teamMembers'=>function ($q) {
                        $q->doesntHave('mapminerUser')
                          ->where('job_code', 103);
                      }
                ]
            )->with('mapminerUser.person')
            ->get();
        foreach ($managers as $manager) {
            Mail::to([$manager->mapminerUser->getFormattedEmail()])
                    
                    ->send(
                        new NotifyMarketManagersMissingBranchManagersMail($manager)
                    );
        }
    }

}
