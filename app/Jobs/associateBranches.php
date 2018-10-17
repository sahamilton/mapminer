<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Person;
use App\UserImport;

class associateBranches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $person;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserImport $person)
    {
        $this->person = $person;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
                $branches = explode(",",str_replace(' ','',$this->person->branches));
              
                foreach ($branches as $branch){
                    $data[$branch]=['role_id' => $this->person->role_id]; 
                }
                $person = Person::findOrFail($person->person_id);
                $person->branchesServiced()->sync($data);
            
    }
}