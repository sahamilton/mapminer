<?php

namespace App\Observers;

use App\Jobs\WonOpportunity;
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
417',
             '2685',
             '2686',
             '2684',
             '3401',
             '3416',
             '3413',
             '1455',
             '1050',
            '1055',
            '1056',
            '1058',
            '1059',
            '1060',
            '1061',
            '1062',
            '1064',
            '1066',
            '1067',
            '1676',
            '1677',
            '1678',
            '1679',
            '1684',
            '1686',
            '1687',
            '1689',
            '1691',
            '1693',
            '1708',
            '2203',
            '2205',
            '2206',
            '2207',
            '2208',
            '2209',
            '2210',
            '2212',
            '2213',
            '2214',
            '2215',
            '2216',
            '2218',
            '2220',
            '2221',
            '2225',
            '2230',
            '2666',
            '7254',
            '8020',
            '8037',
            '8058',
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
