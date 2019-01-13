<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;

class updateUserRoles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $users;
    public $newuser;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $newuser, User $users)
    {
        $this->users = $users;
        $this->newuser = $newuser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->users as $user){

            // send to queue
            $roles = explode(",",$this->newuser[$user->id]);
            $user->roles()->sync($roles);
        }
    }
}
