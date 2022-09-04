<?php

namespace App\Jobs;

use App\Models\Person;
use App\Models\UserImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class associateBranches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $person;

    /**
     * [__construct description].
     *
     * @param UserImport $person [description]
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
        $branches = explode(',', str_replace(' ', '', $this->person->branches));
        foreach ($branches as $branch) {
            $data[$branch] = ['role_id' => $this->person->role_id];
        }
        $person = Person::findOrFail($person->person_id);
        $person->branchesServiced()->sync($data);
    }
}
