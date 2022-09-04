<?php

namespace App\Listeners\Users;

use App\Models\Track;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLastLoggedInAt
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if (\App::environment() != 'local') {
            $this->updateTrackTable($event);
            $this->updateUserTable();
        }
    }

    private function updateTrackTable($event)
    {
        $data['user_id'] = $event->user->id;
        $data['lastactivity'] = now();
        Track::create($data);
    }

    private function updateUserTable()
    {
        // update the user record for last login
        // we don't want to update the updated_at field for logins. Its redundant.
        $user = auth()->user();
        $user->timestamps = false;
        $user->update(['lastlogin' => now()]);
        $user->timestamps = true;
    }
}
