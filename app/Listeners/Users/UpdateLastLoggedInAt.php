<?php

namespace App\Listeners\Users;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Track;
use Carbon\Carbon;
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
       $this->updateTrackTable($event);
       $this->updateUserTable();
        
    }

    private function updateTrackTable($event){
        $data['user_id'] = $event->user->id;
        $data['lastactivity'] = Carbon::now();
        Track::create($data);
    }

    private function updateUserTable(){
        // update the user record for last login
        // we don't want to update the updated_at field for logins. Its redundant.
        $user = auth()->user();
        $user->timestamps =false;
        $user->update(['lastlogin' => Carbon::now()]);
        $user->timestamps =true;
    }
}
