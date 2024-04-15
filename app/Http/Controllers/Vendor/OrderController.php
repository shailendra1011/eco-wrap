<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use App\Models\UserReserveTable;
use App\Store;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DriverOrderRequest;
use App\Notification;
use App\User;

class OrderController extends Controller
{
    public function getProductOrder(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $orders = Order::where('store_id', Auth::user()->id);
            $orders = $orders->where('order_no', 'LIKE', '%' . $search . '%');
            if ($request->from_date) {
                $orders = $orders->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->to_date) {
                $orders = $orders->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }
            $orders = $orders->with('orderProduct.productDetails.productImages', 'userDetails', 'driverOrder.driverDetails')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            $orderData = [];
            foreach ($orders as $key => $order) {
                $orderData[$key]['order_id'] = $order->id;
                $orderData[$key]['order_no'] = $order->order_no;
                $orderData[$key]['user_id'] = $order->userDetails->id;
                $orderData[$key]['user_name'] = $order->userDetails->name;
                $orderData[$key]['driver_name'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? $order->driverOrder->driverDetails->driver_name : 'Not assigned';
                $orderData[$key]['driver_status'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? 1 : 0;
                $orderData[$key]['is_driver_accepted'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? 1 : 0;
                $orderData[$key]['order_status'] = DriverOrderRequest::where('order_id', $order->id)->count() > 0 ? 2 : $order->order_status;
                $orderData[$key]['created_at'] = $order->created_at;
            }
            $count = Order::where('store_id', Auth::user()->id)->count();
            return response()->json(['data' => $orders, 'orders' => $orderData, 'count' => $count], 200);
        }
        return view('vendor.orders.product.order-list');
    }


    public function getPharmacyOrder(Request $request)
    {

        if ($request->ajax()) {
            $search = $request->search;
            $orders = Order::where('store_id', Auth::user()->id);
            $orders = $orders->where('order_no', 'LIKE', '%' . $search . '%');
            if ($request->from_date) {
                $orders = $orders->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->to_date) {
                $orders = $orders->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }
            $orders = $orders->with('orderProduct.productDetails.productImages', 'userDetails', 'driverOrder.driverDetails')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            $orderData = [];
            foreach ($orders as $key => $order) {
                $orderData[$key]['order_id'] = $order->id;
                $orderData[$key]['order_no'] = $order->order_no;
                $orderData[$key]['user_id'] = $order->userDetails->id;
                $orderData[$key]['user_name'] = $order->userDetails->name;
                $orderData[$key]['driver_name'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? $order->driverOrder->driverDetails->driver_name : 'Not assigned';
                $orderData[$key]['driver_status'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? 1 : 0;
                $orderData[$key]['is_driver_accepted'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? 1 : 0;
                $orderData[$key]['order_status'] = DriverOrderRequest::where('order_id', $order->id)->count() > 0 ? 2 : $order->order_status;
                $orderData[$key]['created_at'] = $order->created_at;
            }
            $count = Order::where('store_id', Auth::user()->id)->count();
            return response()->json(['data' => $orders, 'orders' => $orderData, 'count' => $count], 200);
        }
        return view('vendor.orders.pharmacy.order-list');
    }



    public function getFoodOrder(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $orders = Order::where('store_id', Auth::user()->id);
            $orders = $orders->where('order_no', 'LIKE', '%' . $search . '%');
            if ($request->from_date) {
                $orders = $orders->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->to_date) {
                $orders = $orders->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }
            $orders = $orders->with('orderProduct.productDetails.productImages', 'userDetails', 'driverOrder.driverDetails')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            $orderData = [];
            foreach ($orders as $key => $order) {
                $orderData[$key]['order_id'] = $order->id;
                $orderData[$key]['order_no'] = $order->order_no;
                $orderData[$key]['user_id'] = $order->userDetails->id;
                $orderData[$key]['user_name'] = $order->userDetails->name;
                $orderData[$key]['driver_name'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? $order->driverOrder->driverDetails->driver_name : 'Not assigned';
                $orderData[$key]['driver_status'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? 1 : 0;
                $orderData[$key]['is_driver_accepted'] = $order->driverOrder != null &&  $order->driverOrder->driver_id != null ? 1 : 0;
                $orderData[$key]['order_status'] = DriverOrderRequest::where('order_id', $order->id)->count() > 0 ? 2 : $order->order_status;
                $orderData[$key]['created_at'] = $order->created_at;
            }
            $count = Order::where('store_id', Auth::user()->id)->count();
            return response()->json(['data' => $orders, 'orders' => $orderData, 'count' => $count], 200);
        }
        return view('vendor.orders.food.order-list');
    }

    public function getOrderDetails($orderId)
    {

        $orderDetails = Order::where('store_id', Auth::user()->id)->where('id', $orderId)
            ->with('orderProduct.productDetails.productImages', 'userDetails', 'driverOrder.driverDetails')
            ->first();
        if (Auth::user()->category_id == 1) {
            return view('vendor.orders.food.order-details', ['orderDetails' => $orderDetails]);
        }
        if (Auth::user()->category_id == 2) {
            return view('vendor.orders.pharmacy.order-details', ['orderDetails' => $orderDetails]);
        }
        if (Auth::user()->category_id == 3) {
            return view('vendor.orders.product.order-details', ['orderDetails' => $orderDetails]);
        }
    }
    public function getFoodTableOrder(Request $request)
    {
        if ($request->ajax()) {

            $search = $request->search;
            $orders = UserReserveTable::where('store_id', Auth::user()->id);
            $orders = $orders->where('booking_person_name', 'LIKE', '%' . $search . '%');
            if ($request->from_date) {
                $orders = $orders->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->to_date) {
                $orders = $orders->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }
            $orders = $orders->with('user')->orderBy('created_at', 'DESC')->paginate(10);
            $count = UserReserveTable::where('store_id', Auth::user()->id)->count();
            return response()->json(['data' => $orders, 'count' => $count], 200);
        }
        return view('vendor.orders.food.hall-order-list');
    }
    public function changeFoodTableOrderStatus(Request $request)
    {
        try {
            $status = UserReserveTable::where('id', $request->order_id)->update(['status' => $request->status]);
            if ($status) {
                $msg = $request->status == 1 ? 'Order accepted successfully' : 'Order cancelled successfully';
                return response()->json(['message' => $msg, 'status' => 200]);
            } else {
                return response()->json(['message' => 'Something went wrong', 'status' => 401]);
            }
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage(), 'status' => 500]);
        }
    }


    public function changeOrderStatus(Request $request)
    {
        try {
            $status = $request->status;
            if ($status == 1) {
                $storeId = Order::where('id', $request->order_id)->select('store_id')->first();

                $driverOrder = DriverOrder::create([
                    'store_id' => $storeId->store_id,
                    'order_id' => $request->order_id,
                    'accepted_at' => Carbon::now()
                ]);
                $status = Order::where('id', $request->order_id)->update(['order_status' => $request->status]);
                $order = Order::where('id', $request->order_id)->first();
                $user = User::where('id',$order->user_id)->first();
                if ($status) {
                    if ($user->device_token) {
                        sendFCM_notification('user','Order status', 'Your order is accepted', $user->device_token);
                        $notification = new Notification;
                        $notification->user_id = $order->user_id;
                        $notification->order_id = $order->id;
                        $notification->notification = 'Your order is accepted';
                        $notification->save();
                    }
                    return response()->json(['message' => 'Order accepted', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'Something went wrong', 'status' => 401]);
                }
            }
            if ($status == 2) {
                $storeId = Order::where('id', $request->order_id)->select('store_id')->first();

                $storeData = Store::where('id', $storeId->store_id)->first();

                $nearestDriver = Driver::select('id', 'driver_name', DB::raw('cast(6371 * acos(cos(radians(' . $storeData->store_latitude . ')) * cos(radians(drivers.driver_lattitude)) * cos(radians(drivers.driver_longitude) - radians(' . $storeData->store_longitude . ')) + sin(radians(' . $storeData->store_latitude . ')) * sin(radians(drivers.driver_lattitude))) as decimal(10,2)) as distance'))
                    ->having('distance', '<=', '100')->get();
                if (count($nearestDriver) > 0) {
                    foreach ($nearestDriver as $driver) {
                        $driverOrderRequest = DriverOrderRequest::create([
                            'order_id' => $request->order_id,
                            'store_id' => $storeId->store_id,
                            'driver_id' => $driver->id
                        ]);

                        if ($driver->device_token) {
                            sendFCM_notification('driver','Order status', 'New Order received.', $driver->device_token);
                        }

                    }

                    return response()->json(['message' => 'Driver request sent', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'No nearest drivers available', 'status' => 401]);
                }
            }
            if ($status == 3) {
                $deliveryDate = Carbon::parse($request->expectedDeliveryDate)->format('Y-m-d');
                $status = Order::where('id', $request->order_id)->update(['order_status' => $request->status]);
                $status = DriverOrder::where('order_id', $request->order_id)->update(['order_status' => 3, 'shiped_at' => Carbon::now(), 'outfordelivery_at' => Carbon::now(), 'expected_delivery_time' => $deliveryDate]);
                $order = Order::where('id', $request->order_id)->first();
                $user = User::where('id',$order->user_id)->first();

                if ($status) {
                    if ($user->device_token) {
                        sendFCM_notification('user','Order status', 'Out for delivery', $user->device_token);
                        $notification = new Notification;
                        $notification->user_id = $order->user_id;
                        $notification->order_id = $order->id;
                        $notification->notification = 'Your order is Out for delivery';
                        $notification->save();
                    }
                    return response()->json(['message' => 'Order picked & out for delivery', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'Something went wrong', 'status' => 401]);
                }
            }
            if ($status == 5) {
                $status = Order::where('id', $request->order_id)->update(['order_status' => $request->status, 'cancelled_by' => 2, 'cancelled_at' => Carbon::now()]);
                $order = Order::where('id', $request->order_id)->first();
                $user = User::where('id',$order->user_id)->first();
              
                if ($status) {
                    if ($user->device_token) {
                        sendFCM_notification('user','Order status', 'Your order is cancelled', $user->device_token);
                        $notification = new Notification;
                        $notification->user_id = $order->user_id;
                        $notification->order_id = $order->id;
                        $notification->notification = 'Your order is Out for delivery';
                        $notification->save();
                    }
                    return response()->json(['message' => 'Order cancelled', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'Something went wrong', 'status' => 401]);
                }
            }
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage(), 'status' => 500]);
        }
    }



    public function recentOrder()
    {
        $orders = Order::where('store_id', Auth::user()->id)->with('userDetails')->orderBy('created_at', 'DESC')->limit(5)->get();
        $data['order'] = $orders;
        $data['order_count'] = $this->getOrderCount();
        $data['revenue'] = $this->getRevenueSum();
        return response()->json(['data' => $data], 200);
    }


    public function getOrderCount()
    {
        //  Getting vendor views month wise data
        $orderCount   =   Order::select(DB::raw('COUNT(id) as total_count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where(['store_id' => Auth::user()->id,])
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();

        //  Initializing month variables value to 0
        $January = $February = $March = $April = $May = $June = $July = $August = $September = $October = $November = $December = 0;

        //  Declaring month array which consists of months name
        $month = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];

        foreach ($orderCount as $val) {

            foreach ($month as $m) {

                if ($m == $val->monthname) {

                    $$m = $val->total_count;
                }
            }
        }

        $order_count =   [$January, $February, $March, $April, $May, $June, $July, $August, $September, $October, $November, $December];
        return $order_count;
    }
    public function getRevenueSum()
    {
        //  Getting vendor views month wise data
        $orderCount   =   Order::select(DB::raw('SUM(total_price) as total_revenue'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where(['store_id' => Auth::user()->id,])
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();

        //  Initializing month variables value to 0
        $January = $February = $March = $April = $May = $June = $July = $August = $September = $October = $November = $December = 0;

        //  Declaring month array which consists of months name
        $month = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];

        foreach ($orderCount as $val) {

            foreach ($month as $m) {

                if ($m == $val->monthname) {

                    $$m = $val->total_revenue;
                }
            }
        }

        $revenue =   [$January, $February, $March, $April, $May, $June, $July, $August, $September, $October, $November, $December];
        return $revenue;
    }
}
