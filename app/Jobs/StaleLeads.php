<?php

namespace App\Jobs;


use App\Person;
use Excel;
use App\Exports\StaleLeadsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\Collection;

class StaleLeads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $file;
    public $leads;
    public $manager;
    /**
     * [__construct description]
     * 
     * @param Address $leads   [description]
     * @param Person  $manager [description]
     * @param String  $file    [description]
     */
    public function __construct(Collection $leads, Person $manager, String $file)
    {
        $this->leads = $leads;
        $this->manager = $manager;
        $this->file =  $file;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::store(new StaleLeadsExport($this->leads, $this->manager),  '/public/'.$this->file);
        
    }
}
