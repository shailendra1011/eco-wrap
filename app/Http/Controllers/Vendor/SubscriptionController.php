<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Store;
use App\StorePlan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Money\Exchange;

class SubscriptionController extends Controller
{
    public function plans()
    {

        $plans = StorePlan::first();
        $store = Store::find(Auth::user()->id);
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
        $data = (object) [
            'is_cancelled' => $isPlanCancelled,
            'is_trial' => $trial != null ? 1 : 0,
            'remaining_days' => $days
        ];

        return view('vendor.plan.index', compact('plans', 'data'));
    }

    /**
     * Show the Plan.
     *
     * @return mixed
     */
    public function show(StorePlan $plan, Request $request)
    {
        $paymentMethods = $request->user()->paymentMethods();

        $intent = $request->user()->createSetupIntent();

        return view('vendor.plan.show', compact('plan', 'intent'));
    }

    public function create(Request $request, StorePlan $plan)
    {

        try {

            $plan = StorePlan::findOrFail($request->get('plan'));
            $user = $request->user();
            $paymentMethod = $request->paymentMethod;

            // if ($user->subscribed('default')) {
            //     $user->subscription('default')->swapAndInvoice($plan->stripe_plan);
            // } else {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->newSubscription('default', $plan->stripe_plan)
                ->create($paymentMethod, [
                    'email' => $user->email,
                ]);
            // }
            Store::where('id', Auth::user()->id)->update(['trial_ends_at' => null]);
            return back()->with(['message' => 'Your plan subscribed successfully', 'type' => 'success']);
        } catch (Exception $ex) {
            return back()->with(['message' => $ex, 'type' => 'failed']);
        }
    }

    public function cancel()
    {
        //cancel not working
        try {

            $user = Store::where('id', Auth::user()->id)->first();
            $user->subscription('default')->cancel();
            return back()->with(['message' => 'Your plan cancelled successfully', 'type' => 'success']);
        } catch (Exception $ex) {
            return back()->with(['message' => $ex->getMessage(), 'type' => 'failed']);
        }
    }
}
