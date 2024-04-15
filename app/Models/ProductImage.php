<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'product_image'
    ];
    public function getProductImageAttribute($image)
    {
        return url($image);
    }
}
