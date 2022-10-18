<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OracleImportComplete;
use App\Models\OracleSource;
use App\Models\User;
use Mail;

class OracleImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $source;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OracleSource $oraclesource)
    {
        $this->source = $oraclesource;
       
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->source->load('user.person');
        $distribution = $this->_getDistribution();

        Mail::to($distribution)->send(new OracleImportComplete($this->source));
    }
    /**
     * 
     */
    private function _getDistribution()
    {
        $managers = User::with('person')
            ->whereIn('id', [1, 4215])
            ->get();
        $list = [];
        foreach ($managers as $mgr) {
            $list[] = $mgr->getFormattedEmail();
        }

        return $list;
    }
}
