<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorePlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'stripe_plan',
        'cost',
        'description'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
