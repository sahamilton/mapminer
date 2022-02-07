<?php

namespace App\Providers;

use App\Observers\PersonObserver;
use App\Observers\BranchObserver;
use App\Observers\ActivityObserver;
use App\Observers\OpportunityObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Person;
use App\Activity;
use App\Branch;
use App\Location;
use App\Opportunity;
use App\Project;
use App\Lead;
use App\User;

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
        Activity::observe(ActivityObserver::class);
        Person::observe(PersonObserver::class);
        Branch::observe(BranchObserver::class);
        User::observe(UserObserver::class);
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
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
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
