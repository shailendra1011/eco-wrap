<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponCode extends Model
{
    use SoftDeletes;
    
    protected $fillable     =   ['coupon_type','vendor_id','discount_type','discount_value','start_date','end_date','description'];

    
}
