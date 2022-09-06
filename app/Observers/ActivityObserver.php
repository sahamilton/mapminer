<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\AddressBranch;

class ActivityObserver
{
    /**
     * Handle the Activity "created" event.
     *
     * @param \App\Activity  $activity
     * 
     * @return void
     */
    public function created(Activity $activity)
    {
        if ($activity->completed ==1) {
            $addressBranch = AddressBranch::where('branch_id', $activity->branch_id)
                ->where('address_id', $activity->address_id)->first();
            if ($addressBranch->last_activity < $activity->activity_date) {
                $addressBranch->update(['last_activity'=>$activity->activity_date]);
            }
             
            
        } 

    }

    /**
     * Handle the Activity "updated" event.
     *
     * @param \App\Activity  $activity
     * 
     * @return void
     */
    public function updated(Activity $activity)
    {
        //
    }

    /**
     * Handle the Activity "deleted" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function deleted(Activity $activity)
    {
        //
    }

    /**
     * Handle the Activity "restored" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function restored(Activity $activity)
    {
        //
    }

    /**
     * Handle the Activity "force deleted" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function forceDeleted(Activity $activity)
    {
        //
    }
}
