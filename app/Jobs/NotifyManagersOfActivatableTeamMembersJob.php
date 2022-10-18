<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotifyManagersOfActivatableTeamMembers;
use App\Models\Oracle;
use Mail;

class NotifyManagersOfActivatableTeamMembersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $managers;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->managers = Oracle::query()
            ->whereHas('teamMembers',function($q) {
              $q->where('job_code', '103')
                ->doesntHave('mapminerUser');
            })
            
            ->get();
   
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->managers as $manager) {
            Mail::queue(new NotifyManagersOfActivatableTeamMembers($manager));
        }
    }
}
