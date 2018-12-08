<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Person;

class associateIndustries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $people;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($people,array $validIndustries)
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
                $industries = explode(",",$peep->industry);
                $ids = array_keys(array_intersect($validIndustries,$industries));

                $person = Person::findOrFail($peep->person_id);
                $person->industryfocus()->sync($ids);
            }
    }
}
