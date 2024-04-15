<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverSubscription;
use App\Store;
use App\StorePlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StorePlanController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }
    public function plans()
    {
        $plans = StorePlan::get();
        return view('admin.plan', compact('plans'));
    }

    public function storePlan(Request $request)
    {

        $data = $request->except('_token');

        $data['slug'] = strtolower($data['name']);
        $price = $data['cost'] * 100;

        //create stripe product
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
            'description' => $data['description']
        ]);

        //Stripe Plan Creation
        $stripePlanCreation = $this->stripe->plans->create([
            'amount' => $price,
            'currency' => 'USD',
            'interval' => 'month', //  it can be day,week,month or year
            'product' => $stripeProduct->id

        ]);

        $data['stripe_plan'] = $stripePlanCreation->id;
        StorePlan::create($data);
        return back()->with(['message' => 'plan created', 'type' => 'success']);
    }

    public function getSubscribedData($type)
    {
        if ($type == 1) {
            $driverSubscription = DriverSubscription::get();
            $planData = [];
            $earning = 0;
            if (count($driverSubscription)) {
                foreach ($driverSubscription as $subscription) {
                    $earning += $subscription->count * $subscription->price;
                    $days = 0;
                    $trial = $subscription->expired_at;
                    $to = Carbon::parse($trial);
                    $from = Carbon::now();

                    if ($trial > $from) {
                        $days = $to->diffInDays($from);
                    }
                    $planData[] = (object)[
                        'name' => $subscription->driverDetails->driver_name,
                        'status' => $subscription->driverDetails->isSubscribed,
                        'is_trial' => $days > 30 ? 1 : 0
                    ];
                }
            }
            return view('admin.subscription.driver', ['data' => $planData, 'earning' => $earning]);
        }


        if ($type == 2) {
            $stores = Store::get();
            $planData = [];
            $earning = 0;
            foreach ($stores as $store) {
                $plans = StorePlan::first();
                $isPlanCancelled = 0;
                $trial = 0;
                $days = 0;
                if ($store->stripe_id) {
                    $isPlanCancelled = $store->subscription('default')->cancelled() ? 1 : 0;
                }
                if ($store->stripe_id) {
                    $gettingCurrentPlan = $store->subscription('default')->stripe_plan;
                    $checkingstorePlanIsActive = $store->subscribed('default');
                    $plans->isSubscribed = $checkingstorePlanIsActive && $plans->stripe_plan == $gettingCurrentPlan ? 1 : 0;
                } else {
                    $trial = $store->trial_ends_at;
                    $to = Carbon::parse($trial);
                    $from = Carbon::now();

                    if ($trial > $from) {
                        $days = $to->diffInDays($from);
                    }
                }
                if ($plans->isSubscribed) {
                    $sub = DB::table('subscriptions')->where('store_id', $store->id)->first();
                    $subItem = DB::table('subscription_items')->where('subscription_id', $sub->id)->count();
                    $earning += $plans->cost * $subItem;
                }

                $planData[] = (object) [
                    'vendor_name' => $store->store_name ?? $store->store_name_es,
                    'plan_status' => $plans->isSubscribed,
                    'is_cancelled' => $isPlanCancelled,
                    'is_trial' => $trial != null ? 1 : 0,
                    'remaining_days' => $days
                ];
            }

            return view('admin.subscription.vendor', ['data' => $planData, 'earning' => $earning]);
        }
    }
}
