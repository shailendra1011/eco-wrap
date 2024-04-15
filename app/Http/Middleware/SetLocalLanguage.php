<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SetLocalLanguage
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
        
        if (!$request->wantsJson()) {
            if (Session::has('locale')) {

                app()->setLocale(Session::get('locale'));
            } else {
                Session::put('locale', 'en');
                app()->setLocale(Session::get('locale'));
                Session::put('locale_language','English');
            }
        } else {
            $local = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';
            
            // set laravel localization
            app()->setLocale($local);
        }
        return $next($request);
    }
}
