<?php

namespace App\Observers;

use App\Person;
use App\Jobs\WonOpportunity;
use App\Opportunity;

class OpportunityObserver
{
    // limit to eric lynn's branches
    public $branches = [ 
            Person::find(647)->getMyBranches();
         ];
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
     * @param \App\Opportunity $opportunity 
     * 
     * @return void
     */
    public function updated(Opportunity $opportunity)
    {
        // limiting to Eric Lynn's branches
        if ($opportunity->closed == 1 && in_array($opportunity->branch_id, $this->branches)) {
            
             WonOpportunity::dispatch($opportunity);
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
