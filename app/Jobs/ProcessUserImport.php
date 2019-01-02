<?php

namespace App\Jobs;
use App\UserImport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessUserImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Userimport $import)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // update user record
        $user= User::findOrFail($userimport->user_id);
        $user->email = $userimport->email;
        $user->save();
        // update roles
        if(is_array($roles = explode(",",[$userimport->role_id))){
                $user->roles()->sync($roles);
            }else{
                $user->roles()->sync([]);
            }

        // update servicelines
        if(is_array($servicelines = ",",$userimport->serviceline)){
            $user->serviceline()->sync(explode(",",$userimport->serviceline));
        }else{
            $user->serviceline()->sync([]));
        }
        // update person record
        $person = Person::findOrFail($userimport->person_id);
        $person->update($userimport->toArray());
        // update branch assignments  //
        //Not sure we want to do this until we get a good list
       /* if(is_array($branches = explode(",",$userimport->branches)){
            $person->branchesServiced()->sync($branches));
        }else{
            $person->branchesServiced()->sync([]));
        }*/
        $userimport->imported = 1;
        $userimport->save();
    }
}
