<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class updateUserRoles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $users;
    public $newuser;

    /**
     * [__construct description].
     *
     * @param array $newuser [description]
     * @param User  $users   [description]
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
        foreach ($this->users as $user) {
            // send to queue
            $roles = explode(',', $this->newuser[$user->id]);
            $user->roles()->sync($roles);
        }
    }
}
