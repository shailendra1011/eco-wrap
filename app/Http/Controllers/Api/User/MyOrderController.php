<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use App\Models\ProductImage;
use App\Models\StoreImage;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MyOrderController extends Controller
{
    public function myOrder(Request $request)
    {
        $orders = Order::select('id', 'order_no', 'order_status')
            ->with('orderProductDetail')
            ->where('user_id', Auth::user()->id);
        if ($request->order_type == 1) {
            $orders = $orders->whereIn('order_status', [0, 1, 2, 3])->get();
        } else {
            $orders = $orders->where('order_status', 4)->get();
        }
        
        foreach ($orders as $order) {
            foreach ($order->orderProductDetail as $product) {   
                if ($order->order_status == 4) {
                    $isDelivered = DriverOrder::where('order_id', $product->order_id)->first();
                    $current_time = Carbon::now();
                    $to = Carbon::createFromFormat('Y-m-d H:s:i', $current_time);
                    $from = Carbon::createFromFormat('Y-m-d H:s:i', $isDelivered->delivered_at);
                    $diff = $to->diffInDays($from);
                    if ($diff < 4) {
                        $product->isReturn = 'true';
                    }
                } else {
                    $product->isReturn = 'false';
                }
                $user = User::where('id', Auth::user()->id)->first(); 
                if ($user->user_language == 'es') {
                    $product->productDetail->product_name = $product->productDetail->product_name_es;
                    $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name_es;
                } else {
                    $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name;
                }
                $product->isCancelled = $product->isCancelled == 1 ? 'true' : 'false';
                $product_image = ProductImage::where('product_id', $product->product_id)->first();
                $product->productDetail->product_image = $product_image->product_image;
                // unset($product->productDetail->price);
                // unset($product->productDetail->discount);
                // unset($product->productDetail->quantity);
                // unset($product->productDetail->subcategory_id);
                // unset($product->productDetail->sub_category);
            }
        }
        return res(200, trans('CustomMessages.user.signup_success'), $orders);
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
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->user_language == 'es') {
                $order->productDetail->product_name = $order->productDetail->product_name_es;
                $order->productDetail->subcategory_name = $order->productDetail->sub_category->subcategory_name_es;
            } else {
                $order->productDetail->subcategory_name = $order->productDetail->sub_category->subcategory_name;
            }
            $order->isCancelled = $order->isCancelled == 1 ? 'true' : 'false';
            $image = ProductImage::where('product_id', $order->product_id)->first();
            $order->productDetail->product_image = $image->product_image;
            // unset($order->productDetail->price);
            // unset($order->productDetail->discount);
            // unset($order->productDetail->quantity);
            // unset($order->productDetail->subcategory_id);
            // unset($order->productDetail->sub_category);
        }
        return res(200, trans('CustomMessages.user.signup_success'), ['order_detail' => $orderDetail]);
    }

    public function trackOrder(Request $request)
    {
        $order = DriverOrder::select('id', 'order_id', 'driver_id', 'is_driver_accepted', 'order_status', 'expected_delivery_time', 'created_at')
            ->with('orderProductDetails')
            ->where('order_id', $request->order_id)
            ->first();
        $driver_order = DriverOrder::where('id',$order->id)->first();
        $driver = Driver::where('id', $order->driver_id)->first();
        $order->driver_mobile = $driver->driver_mobile;
        unset($order->driver_id);
        foreach ($order->orderProductDetails as $product) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->user_language == 'es') {
                $product->productDetail->product_name = $product->productDetail->product_name_es;
                $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name_es;
            } else {
                $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name;
            }
            $product->isCancelled = $product->isCancelled == 1 ? 'true' : 'false';
            $image = ProductImage::where('product_id', $product->product_id)->first();
            $product->productDetail->product_image = $image->product_image;
            unset($product->order_id);
            unset($product->created_at);
            unset($product->updated_at);
            unset($product->productDetail->price);
            unset($product->productDetail->discount);
            unset($product->productDetail->quantity);
            unset($product->productDetail->subcategory_id);
            unset($product->productDetail->sub_category);
        }
        // $track_order_status = [['order_recieved' => false], ['order_pickup' => false], ['out_for_delivery' => false], ['order_delivered' => false]];
        // if ($order->order_status == 1 || $order->order_status == 2) {
        //     $track_order_status[0]['date'] = $driver_order->created_at;
        //     $track_order_status[0]['order_recieved'] = true;
        // } elseif ($order->order_status == 3) {
        //     $track_order_status[0]['date'] = $driver_order->accepted_at;
        //     $track_order_status[1]['date'] = $driver_order->shiped_at;
        //     $track_order_status[2]['date'] = $driver_order->outfordelivery_at;
        //     $track_order_status[0]['order_recieved'] = true;
        //     $track_order_status[1]['order_pickup'] = true;
        //     $track_order_status[2]['out_for_delivery'] = true;
        // } elseif ($order->order_status == 4) {
        //     $track_order_status[0]['date'] = $driver_order->accepted_at;
        //     $track_order_status[1]['date'] = $driver_order->shiped_at;
        //     $track_order_status[2]['date'] = $driver_order->outfordelivery_at;
        //     $track_order_status[3]['date'] = $driver_order->delivered_at;
        //     $track_order_status[0]['order_recieved'] = true;
        //     $track_order_status[1]['order_pickup'] = true;
        //     $track_order_status[2]['out_for_delivery'] = true;
        //     $track_order_status[3]['order_delivered'] = true;
        // }
        $track_order_status = (object)['order_recieved' => false, 'order_pickup' => false, 'out_for_delivery' => false, 'order_delivered' => false];
        if ($order->order_status == 1 || $order->order_status == 2) {
            $track_order_status->order_recieved = true;
        } elseif ($order->order_status == 3) {
            $track_order_status->order_recieved = true;
            $track_order_status->order_pickup   = true;
            $track_order_status->out_for_delivery = true;
        } elseif ($order->order_status == 4) {
            $track_order_status->order_recieved = true;
            $track_order_status->order_pickup   = true;
            $track_order_status->out_for_delivery = true;
            $track_order_status->order_delivered  = true;
        }
        $track_order_timing = (object)['order_recieved' => false, 'order_pickup' => false, 'out_for_delivery' => false, 'order_delivered' => false];
        if ($order->order_status == 1 || $order->order_status == 2) {
            $track_order_timing->order_recieved = $driver_order->created_at;
        } elseif ($order->order_status == 3) {
            $track_order_timing->order_recieved = $driver_order->accepted_at;
            $track_order_timing->order_pickup   = $driver_order->shiped_at;
            $track_order_timing->out_for_delivery = $driver_order->outfordelivery_at;
        } elseif ($order->order_status == 4) {
            $track_order_timing->order_recieved = $driver_order->accepted_at;
            $track_order_timing->order_pickup   = $driver_order->shiped_at;
            $track_order_timing->out_for_delivery = $driver_order->outfordelivery_at;
            $track_order_timing->order_delivered  = $driver_order->delivered_at;
        }
        // return $order_status[0]['order_recieved']=true;

        return res(200, trans('CustomMessages.user.signup_success'), ['order' => $order, 'track_order_status' => $track_order_status,'track_order_time' => $track_order_timing]);
    }

    public function itemDetail(Request $request)
    {
        $product = OrderProduct::select('order_id', 'product_id', 'quantity', 'discount', 'price')
            ->with('productDetail')
            ->where('id', $request->id)
            ->first();
        $product->subtotal = ($product->price) - ($product->discount);
        $order = Order::where('id', $product->order_id)->first();
        $product->shipping_charge = $order->shipping_charge;
        $product->order_no = $order->order_no;
        $product->created_at = $order->created_at;
        $user = User::where('id', Auth::user()->id)->first();
        if ($user->user_language == 'es') {
            $product->productDetail->product_name = $product->productDetail->product_name_es;
            $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name_es;
        } else {
            $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name;
        } 
        $image = ProductImage::where('product_id', $product->product_id)->first();
        $product->productDetail->product_image = $image->product_image;
        unset($product->productDetail->price);
        unset($product->productDetail->discount);
        unset($product->productDetail->quantity);
        unset($product->productDetail->subcategory_id);
        unset($product->productDetail->sub_category);
        return res(200, trans('CustomMessages.user.signup_success'), ['product_detail' => $product]);
    }
}
