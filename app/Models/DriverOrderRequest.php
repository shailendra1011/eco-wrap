<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverOrderRequest extends Model
{
    protected $fillable = ['order_id', 'store_id', 'driver_id'];
    public function orderDetails()
    {
        return $this->hasOne(Order::class, 'id', 'order_id')->select('id', 'user_id', 'store_id', 'order_no')->with('storeDetail')->with('userDetail');
    }
}
