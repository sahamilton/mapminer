<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\User;
use Mail;
use Illuminate\Bus\Queueable;
use App\Mail\SendWeeklyActivityReminder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WeeklyActivityReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
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
        $users = User::whereHas('activities',function ($q){
            $q->whereBetween('activity_date',[Carbon::now(),Carbon::now()->addWeek()])
            ->whereNull('completed');
        })
        ->with(['activities'=>function ($q){
            $q->whereBetween('followup_date',[Carbon::now(),Carbon::now()->addWeek()])
            ->whereNull('completed')
            ->with('relatesToAddress')
            ->orderBy('activity_date');
        }])->with('person')
        ->get();
       
        foreach ($users as $user){
            
            Mail::to($user->email)
                ->cc(config('mapminer.system_contact'),config('mapminer.developer_email'))
                ->send(new SendWeeklyActivityReminder($user,$user->activities));
        }
    }
}