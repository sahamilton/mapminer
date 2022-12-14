<?php

namespace App\Jobs;

use App\Models\Branch;
use App\Mail\SendWeeklyOpportunityReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class WeeklyOpportunitiesReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $branch;

    /**
     * [__construct description].
     *
     * @param Branch $branch [description]
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $branches = Branch::has('opportunitiesClosingThisWeek')
            ->orWhereHas('pastDueOpportunities')
            ->with('opportunitiesClosingThisWeek', 'pastDueOpportunities', 'manager', 'manager.userdetails')
            ->get();

        foreach ($branches as $branch) {
            if ($branch->manager->count() > 0) {
                foreach ($branch->manager as $manager) {
                    Mail::to([['email'=>$manager->userdetails->email, 'name'=>$manager->fullName()]])
                    ->queue(new SendWeeklyOpportunityReminder($branch, $manager));
                }
            }
        }
    }
}
