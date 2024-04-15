<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverSubscription extends Model
{
    protected $fillable = ['subscription_plan_id', 'driver_id', 'transction_id', 'expired_at'];

    public function driverDetails()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id')->select('id', 'driver_name', 'isSubscribed');
    }
}
