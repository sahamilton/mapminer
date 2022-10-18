<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendActivityReminder;
use Mail;
use App\Models\Ical;
use App\Models\User;
use App\Models\Activity;

class SendActivityReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $period;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Array $period)
    {
        
        $this->user = $user;
        $this->period = $period;
           
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ical = new Ical;

        $activities = Activity::where('user_id', $this->user->id)
            ->whereBetween('activity_date', 
                [$this->period['from']->startOfDay(), $this->period['to']->endOfDay()])
            ->whereNull('completed')
            ->with('relatesToAddress')
            ->orderBy('activity_date')
            ->get(); 
        $calendar = $ical->createIcs($activities);    
        Mail::queue(new SendActivityReminder($this->user, $calendar, $activities, $this->period));//
    }
}
