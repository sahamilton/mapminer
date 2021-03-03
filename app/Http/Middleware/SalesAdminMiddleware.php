<?php

namespace App\Http\Middleware;

use Closure;

class SalesAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user() or ! $request->user()->can('manage_leads')) {
            return redirect('home');
        }

        return $next($request);
    }
}
