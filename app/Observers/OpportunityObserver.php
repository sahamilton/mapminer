<?php

namespace App\Observers;

use App\Jobs\WonOpportunity;
use App\Jobs\NewOpportunity;
use App\Opportunity;
use App\Address;


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
        if ($opportunity->value > 50000 && $opportunity->closed ==0) {
            
                NewOpportunity::dispatch($opportunity);
            }
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
        
        if ($opportunity->closed == 1) {
             Address::where('id', $opportunity->address_id)->update(['isCustomer' => 1]);
            if ($opportunity->value > 10000) {
                WonOpportunity::dispatch($opportunity);
            }
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