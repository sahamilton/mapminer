<?php

namespace App\Jobs;

use App\Mail\WonOpportunityNotification;
use App\Models\Opportunity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class WonOpportunity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $opportunity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->opportunity->load('location', 'branch.branch.manager');
        $distribution = $this->_getDistribution();
        @ray($distribution);
        Mail::to($distribution)->send(new WonOpportunityNotification($this->opportunity));
    }

    private function _getDistribution()
    {
        @ray('disty', $this->opportunity->branch->branch->manager);;
        $managers = $this->opportunity->branch->branch->manager->map(
            function ($mgr) {
                return $mgr->ancestorsAndSelf()->with('userdetails')->get();
            }
        );

        $list = [];
        foreach ($managers as $manager) {
            foreach ($manager as $mgr) {
                
                    $list[] = ['name'=>$mgr->fullName(), 'email'=>$mgr->userdetails->email];
                
            }
        }

        return $list;
    }
}
