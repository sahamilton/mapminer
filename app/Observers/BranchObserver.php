<?php

namespace App\Observers;

use App\Branch;
use App\Jobs\RebuildBranchesXMLJob;

class BranchObserver
{
    public $observableEventNames  = [
                "created",
                "updated",
                "deleted",
                "saved",
                "restored",
        ];
    /**
     * [created description]
     * 
     * @param Branch $branch [description]
     * 
     * @return [type]         [description]
     */
    public function created(Branch $branch)
    {
        RebuildBranchesXMLJob::dispatch();
        
    }

    /**
     * [updated description].
     *
     * @param Branch $branch [description]
     *
     * @return [type]         [description]
     */
    public function updated(Branch $branch)
    {
        RebuildBranchesXMLJob::dispatch();
    }

    /**
     * [deleting description].
     *
     * @param Branch $branch [description]
     *
     * @return [type]         [description]
     */
    public function deleting(Branch $branch)
    {
        RebuildBranchesXMLJob::dispatch();
    }
}
