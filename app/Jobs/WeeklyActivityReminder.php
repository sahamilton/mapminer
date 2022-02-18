<?php

namespace App\Jobs;

use App\Mail\SendWeeklyActivityReminder;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class WeeklyActivityReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $period;

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
        ->with(
            ['activities'=>function ($q) {
                $q->whereBetween('activity_date', [Carbon::now(), Carbon::now()->addWeek()])
                    ->whereNull('completed')
                    ->with('relatesToAddress')
                    ->orderBy('activity_date');
            }]
        )
       
        ->with('person')
        ->get();
        
        foreach ($users as $user) {
            
            Mail::to([['email'=>$user->email, 'name'=>$user->person->fullName()]])

                ->queue(new SendWeeklyActivityReminder($user));
        }
    }
}
