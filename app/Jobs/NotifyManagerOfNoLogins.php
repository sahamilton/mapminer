<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;
use Mail;
use App\Models\Mail\NotifyManagerOfNoLoginsMail;
use App\Models\Role;
use App\Models\User;

class NotifyManagerOfNoLogins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
   
    public $period;
    public $users;
    
    /**
     * [__construct description]
     * 
     * @param Collection $managers [description]
     * @param Array      $period   [description]
     * @param int        $roletype [description]
     */
    public function __construct(array $users, Array $period)
    {
        $this->users = $users;
        $this->period = $period;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            
        $users = User::with('person', 'roles')
            ->whereIn('id', $this->users)
            ->get();
        foreach ($users as $user) {
            Mail::to([$user->person->distribution()])
                ->send(new NotifyManagerOfNoLoginsMail($user, $this->period));
                sleep(1);
        }
    }


}
