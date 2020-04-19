<?php

namespace App\Http\Middleware;

use Closure;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // $routeUrl = \Route::current()->getActionName();

        //echo $routeUrl;
        // if (in_array($routeUrl, \Session::get('role'))) {
        return $next($request);
        //  }else {
        // return redirect('/sys/login');
        // }

    }
}
