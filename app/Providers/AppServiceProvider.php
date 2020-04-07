<?php

namespace App\Providers;

use App\Branch;
use App\Lead;
use App\Location;
use App\Observers\BranchObserver;
use App\Observers\OpportunityObserver;
use App\Observers\PersonObserver;
use App\Opportunity;
use App\Person;
use App\Project;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
       
        Branch::observe(BranchObserver::class);
        Opportunity::observe(OpportunityObserver::class);
        Relation::morphMap(
            [
            'branch'  => Branch::class,
            'location'  => Location::class,
            'project' =>Project::class,
            'lead' => Lead::class,
            'person'=>Person::class,
            ]
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
