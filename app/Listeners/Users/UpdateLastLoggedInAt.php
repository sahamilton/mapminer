<?php

namespace App\Listeners\Users;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Track;
class UpdateLastLoggedInAt
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $track = new Track;
        $track->user_id = $event->user->id;
        $track->lastactivity = \Carbon\Carbon::now();
        $track->save();
    }
}
