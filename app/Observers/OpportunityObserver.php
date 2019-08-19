<?php

namespace App\Observers;

use App\Opportunity;

class OpportunityObserver
{
    /**
     * Handle the opportunity "created" event.
     *
     * @param  \App\Opportunity  $opportunity
     * @return void
     */
    public function created(Opportunity $opportunity)
    {
        //
    }

    /**
     * Handle the opportunity "updated" event.
     *
     * @param  \App\Opportunity  $opportunity
     * @return void
     */
    public function updated(Opportunity $opportunity)
    {
        if ($opportunity->won == 1) {
            return Mail::queue(new WonOpportunityNotification($opportunity));
        }
    }

    /**
     * Handle the opportunity "deleted" event.
     *
     * @param  \App\Opportunity  $opportunity
     * @return void
     */
    public function deleted(Opportunity $opportunity)
    {
        //
    }

    /**
     * Handle the opportunity "restored" event.
     *
     * @param  \App\Opportunity  $opportunity
     * @return void
     */
    public function restored(Opportunity $opportunity)
    {
        //
    }

    /**
     * Handle the opportunity "force deleted" event.
     *
     * @param  \App\Opportunity  $opportunity
     * @return void
     */
    public function forceDeleted(Opportunity $opportunity)
    {
        //
    }
}
