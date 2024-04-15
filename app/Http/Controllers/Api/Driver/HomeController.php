<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\DriverOrder;
use App\Models\DriverOrderRequest;
use App\Models\Order;
use App\Models\ProductImage;
use App\Models\StoreImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function incomingOrders()
    {
        $orders = DriverOrderRequest::select('id', 'order_id', 'driver_id')
            ->with('orderDetails')
            ->where('driver_id', Auth::user()->id)
            ->get();
        foreach ($orders as $order) {
            $store       = Order::where('id', $order->order_id)->first();
            $store_image = StoreImage::where('store_id', $store->store_id)->first();
            $order->orderDetails->storeDetail->store_image = $store_image->store_image;
            // $order->isAccepted = $order->is_driver_accepted == 1 ? "true" : "false";
        }
        $ongoing_orders = DriverOrder::where('driver_id', Auth::user()->id)->whereIn('order_status', [2, 3])->count();
        $AllOrders      = DriverOrder::where('driver_id', Auth::user()->id)->where('order_status', 4)->count();
        $total_revenue  = Order::where('driver_id', Auth::user()->id)->sum('shipping_charge');
        return res(200, trans('CustomMessages.user.signup_success'), ['order' => $orders, 'allOrders' => $AllOrders, 'ongoingOrders' => $ongoing_orders, 'total_revenue' => $total_revenue]);
    }

    public function driverOnlineStatus(Request $request)
    {
        $data = $request->validate([
            'online_status'   => 'required'
        ]);
        $status_update = Driver::where('id', Auth::user()->id)->update(['driver_status' => $request->online_status]);
        if ($status_update) {
            $driver = Driver::where('id', Auth::user()->id)->first();
            return res(200, trans('CustomMessages.user.signup_success'), ['driver' => $driver->driver_status]);
        }
    }

    public function driverAcceptOrder(Request $request)
    {
        $data = $request->validate([
            'accept_order'   => 'required',
            'order_id'       => 'required'
        ]);
        $isAssign = DriverOrder::where('order_id', $request->order_id)->first();
        if ($isAssign->order_status == 1) {
            $status_update = DriverOrder::where('order_id', $request->order_id)->update(['is_driver_accepted' => $request->accept_order, 'order_status' => 2, 'driver_id' => Auth::user()->id]);
            if ($request->accept_order == 1) {
                DriverOrderRequest::where('order_id', $request->order_id)->delete();
                Order::where('id', $request->order_id)->update(['order_status' => 2, 'driver_id' => Auth::user()->id]);
            }
            if ($status_update) {
                return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
            }
        } else {
            return res(200, trans('CustomMessages.driver.no_incoming_order'), (object)[]);
        }
    }

    public function onoingOrder(Request $request)
    {
        $orders = DriverOrder::select('id', 'order_id', 'driver_id')
            ->with('orderDetails')
            ->where('driver_id', Auth::user()->id)
            ->whereIn('order_status', [2, 3])
            ->get();
        foreach ($orders as $order) {
            $store       = Order::where('id', $order->order_id)->first();
            $store_image = StoreImage::where('store_id', $store->store_id)->first();
            $order->orderDetails->storeDetail->store_image = $store_image->store_image;
            // $order->isAccepted = $order->is_driver_accepted == 1 ? "true" : "false";
        }
        return res(200, trans('CustomMessages.user.signup_success'), ['order' => $orders]);
    }

    public function statusUpdate(Request $request)
    {
        $order = DriverOrder::select('id', 'order_id', 'driver_id', 'is_driver_accepted', 'order_status', 'expected_delivery_time', 'created_at')
            ->with('orderDetails')
            ->where('order_id', $request->order_id)
            ->first();
        $store       = Order::where('id', $order->order_id)->first();
        $store_image = StoreImage::where('store_id', $store->store_id)->first();
        $order->orderDetails->storeDetail->store_image = $store_image->store_image;
        // $order->isAccepted = $order->is_driver_accepted == 1 ? "true" : "false";

        $order_status = (object)['order_recieved' => false, 'order_pickup' => false, 'out_for_delivery' => false, 'order_delivered' => false];
        if ($order->order_status == 1 || $order->order_status == 2) {
            $order_status->order_recieved = true;
        } elseif ($order->order_status == 3) {
            $order_status->order_recieved = true;
            $order_status->order_pickup   = true;
            $order_status->out_for_delivery = true;
        } elseif ($order->order_status == 4) {
            $order_status->order_recieved = true;
            $order_status->order_pickup   = true;
            $order_status->out_for_delivery = true;
            $order_status->order_delivered  = true;
        }

        return res(200, trans('CustomMessages.user.signup_success'), ['order' => $order, 'order_status' => $order_status]);
    }

    public function orderDetail(Request $request)
    {
        $orderDetail = Order::select('id', 'user_id', 'order_no', 'total_price', 'shipping_charge', 'delivery_address_id', 'payment_mode', 'created_at')
            ->with('orderProductDetail')
            ->with('deliverTo')
            ->where('id', $request->order_id)
            ->first();
        $orderDetail->sub_total = $orderDetail->total_price - $orderDetail->shipping_charge;
        $orderDetail->payment_mode = $orderDetail->payment_mode == 1 ? "online" : " cash on delivery";
        foreach ($orderDetail->orderProductDetail as $order) {
            $image = ProductImage::where('product_id', $order->product_id)->first();
            $order->productDetail->product_image = $image->product_image;
        }
        return res(200, trans('CustomMessages.user.signup_success'), ['order_detail' => $orderDetail]);
    }

    public function orderDelivered(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required'
        ]);
        Order::where('id', $request->order_id)->update(['order_status' => 4]);
        DriverOrder::where('order_id', $request->order_id)->update(['order_status' => 4, 'delivered_at' => Carbon::now()]);
        return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
    }

    public function allOrder(Request $request)
    {
        if ($request->isCompleteOrder == '') {
            $orders = Order::select('id', 'order_no', 'user_id', 'store_id')
                ->with('userDetail')
                ->with('storeDetail')
                ->where('driver_id', Auth::user()->id)
                ->where('driver_id', Auth::user()->id)
                ->where('order_status', 4)
                ->get();
        } elseif ($request->isCompleteOrder == 1) {
            $orders = Order::select('id', 'order_no', 'user_id', 'store_id')
                ->with('userDetail')
                ->with('storeDetail')
                ->where('order_status', 4)
                ->where('driver_id', Auth::user()->id)
                ->get();
        } elseif ($request->isCompleteOrder == 2) {
            $orders = Order::select('id', 'order_no', 'user_id', 'store_id')
                ->with('userDetail')
                ->with('storeDetail')
                ->where('cancelled_by', 1)
                ->where('driver_id', Auth::user()->id)
                ->get();
        }
        foreach ($orders as $order) {
            $store       = Order::where('id', $order->id)->first();
            $store_image = StoreImage::where('store_id', $store->store_id)->first();
            $order->storeDetail->store_image = $store_image->store_image;
            $order->isCancelledOrder = $store->cancelled_by != null ? true : false;
        }
        return res(200, trans('CustomMessages.user.signup_success'), ['order' => $orders]);
    }

    public function allOrderDetail(Request $request)
    {
        $order = Order::select('id', 'delivery_address_id', 'order_no', 'total_price', 'shipping_charge', 'created_at')
            ->with('orderProductDetail')
            ->with('deliverTo')
            ->with('orderStatus')
            ->where('id', $request->order_id)
            ->first();
        $order->sub_total = $order->total_price - $order->shipping_charge;
        $order->payment_mode = $order->payment_mode == 1 ? "online" : " cash on delivery";
        foreach ($order->orderProductDetail as $product) {
            $image = ProductImage::where('product_id', $product->product_id)->first();
            $product->productDetail->product_image = $image->product_image;
        }
       // return $order->orderStatus;
        $order->orderStatus->accepted_at = $order->orderStatus->accepted_at != null ? Carbon::parse($order->orderStatus->accepted_at):null;
        $order->orderStatus->shiped_at = $order->orderStatus->shiped_at != null ? Carbon::parse($order->orderStatus->shiped_at) : null;
        $order->orderStatus->outfordelivery_at = $order->orderStatus->outfordelivery_at != null ? Carbon::parse($order->orderStatus->outfordelivery_at) : null;
        $order->orderStatus->delivered_at = $order->orderStatus->delivered_at != null ?Carbon::parse($order->orderStatus->delivered_at) : null;

       // Carbon::parse($request->date)->timestamp
        
        return res(200, trans('CustomMessages.user.signup_success'), ['order' => $order]);
    }
}
