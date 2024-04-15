<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $loginUser = Auth::user();
        // checkin login user category
        if ($loginUser->category_id == 1) {
            $data = (object) [
                'total_product' => Product::where('store_id', $loginUser->id)->count(),
                'total_revenue' => Order::where('store_id', $loginUser->id)->sum(DB::raw('total_price + shipping_charge')),
                'total_order' => Order::where('store_id', $loginUser->id)->count(),
                'total_compelet_order' => Order::where('store_id', $loginUser->id)->where('order_status', 4)->count(),
                

            ];



            return view('home-food', ['count' => $data]);
        }
        if ($loginUser->category_id == 2) {
            $data = (object) [
                'total_product' => Product::where('store_id', $loginUser->id)->count(),
                'total_revenue' => Order::where('store_id', $loginUser->id)->sum(DB::raw('total_price + shipping_charge')),
                'total_order' => Order::where('store_id', $loginUser->id)->count(),
                'total_compelet_order' => Order::where('store_id', $loginUser->id)->where('order_status', 4)->count(),
                

            ];

            return view('home-pharmacy', ['count' => $data]);
        }
        if ($loginUser->category_id == 3) {
            $data = (object) [
                'total_product' => Product::where('store_id', $loginUser->id)->count(),
                'total_revenue' => Order::where('store_id', $loginUser->id)->sum(DB::raw('total_price + shipping_charge')),
                'total_order' => Order::where('store_id', $loginUser->id)->count(),
                'total_compelet_order' => Order::where('store_id', $loginUser->id)->where('order_status', 4)->count(),
                

            ];

            return view('home-product', ['count' => $data]);
        }
    }


    
}
