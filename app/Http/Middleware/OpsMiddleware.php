<?php

namespace App\Http\Middleware;

use Closure;

class OpsMiddleware
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
        if (! $request->user() or ! $request->user()->can('manage_imports')) {
            return redirect('home');
        }

        return $next($request);
    }
}
