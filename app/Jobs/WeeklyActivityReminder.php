<?php

namespace App\Jobs;


use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class WeeklyActivityReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $period;
    public $timeout = 3600;

    /**
     * [__construct description].
     *
     * @param array $period [description]
     */
    public function __construct(array $period)
    {
        $this->period = $period;
     
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::whereHas(
            'activities', function ($q) {
                $q->whereBetween(
                    'activity_date', [$this->period['from'], $this->period['to']]
                )->whereNull('completed');
            }
        )
        
       
        ->with('person')
        
        ->get();
        
        foreach ($users as $user) {
            @ray($user);
            SendActivityReminderJob::dispatch($user, $this->period);
        }
    }
}
