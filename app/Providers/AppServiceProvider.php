<?php

namespace App\Providers;

use App\Observers\ActivityObserver;
use App\Observers\BranchObserver;
use App\Observers\FeedbackObserver;
use App\Observers\OpportunityObserver;
use App\Observers\OracleSourceObserver;
use App\Observers\PersonObserver;
use App\Observers\UserObserver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


use App\Models\Activity;
use App\Models\Branch;
use App\Models\Feedback;
use App\Models\Lead;
use App\Models\Location;
use App\Models\Opportunity;
use App\Models\OracleSource;
use App\Models\Person;
use App\Models\Project;

use App\Models\User;

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
        Branch::observe(BranchObserver::class);
        Feedback::observe(FeedbackObserver::class);
        Opportunity::observe(OpportunityObserver::class);
        OracleSource::observe(OracleSourceObserver::class);
        Person::observe(PersonObserver::class);
        User::observe(UserObserver::class);
        
        Relation::morphMap(
            [
            'branch'  => Branch::class,
            'lead' => Lead::class,
            'location'  => Location::class,
            'person'=>Person::class,
            'project' =>Project::class,
            ]
        );
        Collection::macro(
            'paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
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
            }
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
