<?php

namespace App\Observers;

use App\Jobs\WonOpportunity;
use App\Opportunity;

class OpportunityObserver
{
    // limit to eric lynn's branches
    public $branches = ['7254',
             '1678',
             '1687',
             '1693',
             '2666',
             '1686',
             '1708',
             '1677',
             '1689',
             '8058',
             '1684',
             '1691',
             '1679',
             '1676',
             '1160',
             '1159',
             '3300',
             '1152',
             '1151',
             '1161',
             '2256',
             '1153',
             '7386',
             '1875',
             '1610',
             '1611',
             '3424',
             '1605',
             '1878',
             '1661',
             '1668',
             '1669',
             '7262',
             '1665',
             '3062',
             '3063',
             '7261',
             '2681',
             '3064',
             '3426',
             '1115',
             '2255',
             '2752',
             '2961',
             '2254',
             '2753',
             '1150',
             '2702',
             '1149',
             '2751',
             '2700',
             '3400',
             '3403',
             '3430',
             '3431',
             '3433',
             '8038',
             '3434',
             '3418',
             '3422',
             '3404',
             '3411',
             '3417',
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
