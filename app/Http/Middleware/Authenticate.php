<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Authenticate extends Middleware
{

/**
 * FUNCTION TEST CREER PAR MAXIME POUR LA REDIRECTION EN CAS DE SESSION EXPIRE
 */

//        public function handle($request, Closure $next, ...$guards)
//    {
//        if (!Auth::check()) {
//
//            Session::flash('message', trans('errors.session_label'));
//            Session::flash('type', 'warning');
//
//            return redirect()->route('home');
//        }
//        return $next($request);
//    }


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {

        if (! $request->expectsJson()) {
            return route('login');
        }
    }


}
