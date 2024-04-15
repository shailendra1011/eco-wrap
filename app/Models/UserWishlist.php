<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWishlist extends Model
{
    protected $fillable =['product_id','user_id'];

    public function product_details()
    {
    	return $this->belongsTo('App\Models\Product','product_id');
    }
}
