<?php

namespace App\Jobs;

use \Carbon\Carbon;
use App\User;
use App\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyBranch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $user;
    public $person;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $period, User $user)
    {
        $this->period = $period;
        $this->user = $user;
        $this->person = Person::where('user_id', $user->id)->findOrFail();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $branches = $this->person->myBranches();

        $data = Branch::summaryStats($this->period)->get();
        dd($data);
        // first get all users - with role ('RVP')?
        // then get their branches - myteam -> my branches
        // then get their data ($this->period); ->map
    }
}
