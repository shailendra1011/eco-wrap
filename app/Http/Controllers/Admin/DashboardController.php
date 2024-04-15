<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\StoreImage;
use App\Store;
use App\User;



class DashboardController extends Controller
{
    public function index()
    {
        $user = User::count();
        $store = Store::count();
        $data = (object) [
            'user' => $user,
            'store' => $store
        ];

        // $item = OrderProduct::select('product_id', DB::raw(SUM(quantity) as 'totalqunt'))->get();
        $items =  OrderProduct::select('product_id', \DB::raw('SUM(quantity) as total_sales'))
            ->groupBy('product_id')
            ->orderByDesc('total_sales')->take(10)
            ->get();
        foreach ($items as $item) { 
            $product = Product::where('id', $item->product_id)->first();
            $vendor=Store::where('id',$product->store_id)->first();
            $image=StoreImage::where('store_id',$product->store_id)->first()->store_image;
            $item->product_name=$product->product_name??$product->product_name_es;
            $item->vendor_name=$vendor->store_name;
            $item->vendor_image = $image;
            $item->mobile = $vendor->store_mobile;
            $item->email = $vendor->email;
        }

        return view('admin.home', ['count' => $data, 'items' => $items]);
    }
}
