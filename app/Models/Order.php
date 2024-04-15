<?php

namespace App\Models;

use App\Store;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'store_id', 'order_no', 'total_price','is_coupon_code_applied','coupon_code_id','coupon_code_discount', 'delivery_address_id', 'payment_mode', 'tax', 'shipping_charge', 'order_status'];

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }
    public function userDetail()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'user_address', 'user_image', 'mobile', 'user_lattitude', 'user_longitude');
    }
    public function storeDetail()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id')->select('id', 'store_name', 'store_address', 'store_mobile', 'store_latitude', 'store_longitude');
    }
    public function orderProductDetail()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id')->select('id', 'order_id', 'product_id','quantity','discount','price','isCancelled')->with('productDetail');
    }
    public function userDetails()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function driverOrder()
    {
        return $this->hasOne(DriverOrder::class, 'order_id', 'id');
    }
    public function deliverTo()
    {
        return $this->hasOne(UserAddress::class,'id','delivery_address_id')->select('id','name','mobile','address','city');
    }
    public function orderStatus()
    {
        return $this->hasOne(DriverOrder::class,'order_id','id')->select('order_id','accepted_at','shiped_at','outfordelivery_at','delivered_at');
    }
    
}
