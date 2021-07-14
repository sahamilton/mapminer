<?php

namespace App\Jobs;

use App\Exports\StaleLeadsExport;
use App\Person;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StaleLeads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $file;
    public $leads;
    public $manager;

    /**
     * [__construct description].
     *
     * @param Address $leads   [description]
     * @param Person  $manager [description]
     * @param string  $file    [description]
     */
    public function __construct(Collection $leads, Person $manager, String $file)
    {
        $this->leads = $leads;
        $this->manager = $manager;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::store(new StaleLeadsExport($this->leads, $this->manager), $this->file, 'public');
    }
}
