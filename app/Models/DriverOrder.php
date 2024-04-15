<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverOrder extends Model
{

    protected $fillable = ['store_id','order_id', 'accepted_at'];
    public function orderDetails()
    {
        return $this->hasOne(Order::class, 'id', 'order_id')->select('id', 'user_id', 'store_id', 'order_no')->with('storeDetail')->with('userDetail');
    }

    public function driverDetails()
    {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }

    public function orderProductDetails()
    {
        return $this->hasMany(OrderProduct::class, 'order_id','order_id')->with('productDetail');
    }
}
