<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Person;

class associateBranches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $people;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($people)
    {
        $this->people = $people;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->people as $peep){
                
                // send to queue
                $branches = explode(",",str_replace(' ','',$peep->branches));
                
                foreach ($branches as $branch){
                    $data[$branch]=['role_id' => $peep->role_id]; 
                }
                $person = Person::findOrFail($peep->person_id);
                $person->branchesServiced()->sync($data);
            }
    }
}
