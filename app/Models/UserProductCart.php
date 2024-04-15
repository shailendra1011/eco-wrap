<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProductCart extends Model
{
    protected $fillable =['user_id','store_id','product_id','price','added_quantity'];

    public function productDetail()
    {
        return $this->belongsTo(Product::class,'product_id','id')->select('id', 'product_name','product_name_es', 'subcategory_id', 'product_name_es', 'price','discount', 'size','quantity')->with('sub_category');
    }

}
