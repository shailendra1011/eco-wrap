<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverSubscription;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

class SubscriptionController extends Controller
{
    public function subscriptionList(Request $request)
    {
        $lists = SubscriptionPlan::select('id', 'package_id', 'subscription_type', 'no_of_customer', 'amount', 'free_trial')->where('device_type', $request->device_type)->get();

        // foreach($lists as $list)
        // {   
        //     $language = Driver::where('id',Auth::user()->id)->first();
        //      if($language->driver_language == 'es'){
        //             $list->subscription_type = $list->subscription_type_es;
        //             $list->no_of_customer    = $list->no_of_customer_es;
        //             $list->amount            = $list->amount_es;
        //          }
        //      $list->package_id = $list->package_id == null?'':$list->package_id; 
        //      unset($list->subscription_type_es);
        //      unset($list->no_of_customer_es);
        //      unset($list->amount_es);
        // }
        return res(200, trans('CustomMessages.user.signup_success'), $lists);
    }

    public function driverBuySubscription(Request $request)
    {
        $data = $request->validate([
            'subscription_plan_id'   => 'required',
        ]);
        $isExist = DriverSubscription::where('driver_id', Auth::user()->id)->first();
        if ($isExist) {
            $isExist->increment('count');
            $date = date('y-m-d', strtotime($isExist->expired_at));
            $exprired_at = date('Y-m-d', strtotime($date . "+30 days"));
            DriverSubscription::where('driver_id', Auth::user()->id)
                ->update([
                    'driver_id' => Auth::user()->id,
                    'subscription_plan_id' => $request->subscription_plan_id,
                    'transction_id' => $request->transction_id,
                    'price' => $request->price,
                    'expired_at' => $exprired_at,

                ]);
            Driver::where('id', Auth::user()->id)
                ->update(['isSubscribed' => 1]);
            return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
        } else {
            $data['driver_id']  = Auth::user()->id;
            $data['transction_id'] =  $request->transction_id;
            $data['price'] =  $request->price;
            $data['expired_at'] = date('Y-m-d', strtotime("+45 days"));
            $save = DriverSubscription::create($data);
            Driver::where('id', Auth::user()->id)
                ->update(['isSubscribed' => 1]);
            return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
        }
    }
}
