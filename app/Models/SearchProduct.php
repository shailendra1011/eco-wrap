<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchProduct extends Model
{
    protected $fillable = ['user_id', 'product_id'];




    public function productDetail()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->select('id','product_name','product_name_es','store_id');
    }
}
