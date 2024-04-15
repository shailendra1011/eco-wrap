<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'product_name',
        'product_name_es',
        'subcategory_id',
        'price',
        'discount',
        'size',
        'quantity',
        'description',
        'description_es',
        'manufacturer_name',
        'manufacturer_name_es',
        'sachet_capsule',
        'direction_to_use',
        'direction_to_use_es',
        'ingredients',
        'ingredients_es',
        'other_info',
        'other_info_es',
        'product_status',
        'delivery_charge',
        'product_name_pt',
        'description_pt',
        'manufacturer_name_pt',
        'direction_to_use_pt',
        'ingredients_pt',
        'other_info_pt'
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getProductImageAttribute($image)
    {
        return url($image);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M Y');
    }

    public function subCategory()
    {
        return $this->hasOne(Subcategory::class,'id','subcategory_id');
    }
    public function sub_category()
    {
        return $this->hasOne(Subcategory::class,'id','subcategory_id')->select('id','subcategory_name','subcategory_name_es');
    }
}
