<?php

namespace App\Jobs;

use App\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class associateIndustries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * [$people description].
     *
     * @var [type]
     */
    public $people;

    /**
     * [__construct description].
     *
     * @param [type] $people          [description]
     * @param array  $validIndustries [description]
     */
    public function __construct($people, array $validIndustries)
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
        foreach ($this->people as $peep) {
            // send to queue
            $industries = explode(',', $peep->industry);
            $ids = array_keys(array_intersect($validIndustries, $industries));

            $person = Person::findOrFail($peep->person_id);
            $person->industryfocus()->sync($ids);
        }
    }
}
