<?php

namespace App\Observers;

use Mail;
use App\User;
use App\Mail\UserChanges;
use App\Mail\UserNotification;
use App\Notifications\UserUpdate;
use App\Jobs\RebuildPeople;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**

    */

    public $observableEventNames  = [
                "creating",
                "created",
                "updating", 
                "updated",
                "deleting",
                "deleted"
               
            ];



    public function created(User $user)
    {
        RebuildPeople::dispatch();
      
        if ($user->confirmed == 1) {
            Mail::queue(new UserNotification($user));
        }
        
    }

    public function updated(User $user)
    {
        RebuildPeople::dispatch();
        
        // Mail::queue(new UserNotification($user));
    }

    public function deleted(User $user)
    {
        RebuildPeople::dispatch();
        Log::info($user->id . " Deleted: People rebuilt");
    }
    
}
