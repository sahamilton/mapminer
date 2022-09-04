<?php

namespace App\Jobs;

use App\Models\Person;
use App\Models\User;
use App\Models\UserImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUserImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $import;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Userimport $import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // update user record
        $user = User::findOrFail($this->import->user_id);
        $user->email = $this->import->email;
        $user->save();
        // update roles
        if (is_array($roles = explode(',', [$this->import->role_id]))) {
            $user->roles()->sync($roles);
        } else {
            $user->roles()->sync([]);
        }

        // update servicelines
        if (is_array($servicelines = ',', $this->import->serviceline)) {
            $user->serviceline()->sync(explode(',', $this->import->serviceline));
        } else {
            $user->serviceline()->sync();
        }
        // update person record
        $person = Person::findOrFail($this->import->person_id);
        $person->update($this->import->toArray());
        // update branch assignments  //
        //Not sure we want to do this until we get a good list
        /* if(is_array($branches = explode(",",$this->import->branches)){
            $person->branchesServiced()->sync($branches));
        }else{
            $person->branchesServiced()->sync([]));
        }*/
        $this->import->imported = 1;
        $this->import->save();
    }
}
