<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreImage extends Model
{
    protected $fillable=['store_id','store_image'];


    public function getStoreImageAttribute($store_image){
        return url($store_image);
    }
}
