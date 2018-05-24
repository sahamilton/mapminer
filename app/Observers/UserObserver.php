<?php 

namespace App\Observers;
Use Mail;
use App\User;
use App\Mail\UserChanges;
use App\Mail\UserNotification;
use App\Notifications\UserUpdate;

class UserObserver 
{
    /**
     * Those are the names of the observable function  
     * 
     * @author Ajay Kumar
     */

    private $observableEventNames  = [
                "creating", 
                "created", 
                "updating", 
                "updated",
                "deleting", 
                "deleted", 
                "saving", 
                "saved",
                "restoring", 
                "restored",
            ];



   public function created(User $user )
    {
       
        Mail::queue(new UserNotification($user));
    }

    
   

}
