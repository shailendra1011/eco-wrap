<?php

namespace App\Http\Middleware;

use App\Store;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckStoreSubscription
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

        // $redirect = redirect()->route('subscription.plan');
        // $store = Store::where('id', Auth::user()->id)->first();
        // $subscription = DB::table('subscriptions')->where('store_id', Auth::user()->id)->first();
        // if ($subscription && $subscription->ends_at && Carbon::now()->gte($subscription->ends_at)) {
        //     return $redirect;
        // } else {
        //     if ($store->trial_ends_at && Carbon::now()->gte($store->trial_ends_at)) {
        //         return  $redirect;
        //     }
        // }

        return $next($request);
    }
}
