<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
      

        parent::boot();
        Route::model('activity', \App\Activity::class);
        Route::model('activitytype', \App\ActivityType::class);
        Route::model('accounttype', \App\AccountType::class);
        Route::model('address', \App\Address::class);
        Route::model('branch', \App\Branch::class);
        Route::model('branchdashboard', \App\Branch::class);
        Route::model('branchlead', \App\BranchLead::class);
        Route::model('company', \App\Company::class);
        Route::model('contact', \App\Contact::class);
        Route::model('customer', \App\Customer::class);
        Route::model('dashboard', \App\Branch::class);
        Route::model('feedback', \App\Feedback::class);
        Route::model('feedback_comment', \App\FeedbackComments::class);
        Route::model('branchsummary', \App\Branch::class);
        Route::model('lead', \App\Lead::class);
        Route::model('leadsource', \App\LeadSource::class);
        Route::model('location', \App\Location::class);
        Route::model('mobile', \App\Address::class);
        Route::model('mylead', \App\Mylead::class);
        Route::model('myleadsactivity', \App\MyLeadActivity::class);
        Route::model('news', \App\News::class);
        Route::model('note', \App\Note::class);
        Route::model('opportunity', \App\Opportunity::class);
        Route::model('permission', \App\Permission::class);
        Route::model('person', \App\Person::class);
        Route::model('role', \App\Role::class);
        Route::model('salesactivity', \App\Salesactivity::class);
        Route::model('salesnote', \App\Company::class);
        Route::model('salesorg', \App\Person::class);
        Route::model('team', \App\Person::class);
        Route::model('track', \App\Track::class);
        Route::model('user', \App\User::class);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapOpsRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

     /**
      * Define the "api" routes for the application.
      *
      * These routes are typically stateless.
      *
      * @return void
      */
    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
            ->middleware(['web', 'admin'])
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }


     /**
      * Define the "api" routes for the application.
      *
      * These routes are typically stateless.
      *
      * @return void
      */
    protected function mapOpsRoutes()
    {
        Route::prefix('ops')
            ->middleware(['web','ops'])
            ->namespace($this->namespace)
            ->group(base_path('routes/ops.php'));
    }
}
