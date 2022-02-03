<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\DirectReportsUsersDeleted;
use App\Person;
use Mail;

class NotifyManagerOfDeletedUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $deleted;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $managers = $this->_getDeletedUsersAndManagers();
        //dd('Job', $this->deleted, $managers);
        foreach ($managers as $manager) {
            Mail::to([$manager->distribution()])
                ->send(new DirectReportsUsersDeleted($manager));
       
        }
    }

    private function _getDeletedUsersAndManagers()
    {
        return Person::withTrashed()
            ->whereHas(
                'directReports', function ($q) {
                    $q->withTrashed()
                        ->whereIn('user_id', $this->deleted);
                }
            )
            ->with(
                'directReports', function ($q) {
                    $q->withTrashed()
                        ->with('directReports') 
                        ->whereIn('user_id', $this->deleted);
                }
            )
        ->get();
    }
}
