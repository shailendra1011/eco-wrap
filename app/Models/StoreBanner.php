<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBanner extends Model
{
    protected $fillable=['store_id','store_banner'];

    public function getStoreBannerAttribute($store_banner){
        return url($store_banner);
    }
}
