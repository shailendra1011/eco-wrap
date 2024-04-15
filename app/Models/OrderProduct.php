<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable =['product_id','order_id','quantity','price'];


    public function productDetails()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function productDetail()
    {
        return $this->hasOne(Product::class,'id','product_id')->select('id','product_name','price','discount','size','quantity','subcategory_id')->with('sub_category');
    }
}
