<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;

class Store extends Authenticatable
{
    use Notifiable, HasApiTokens, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_name',
        'store_image',
        'store_country_code',
        'store_mobile',
        'email',
        'address',
        'store_latitude',
        'store_longitude',
        'store_address',
        'store_url',
        'category_id',
        'password',
        'store_status',
        'device_token',
        'stripe_connect_id',
        'completed_stripe_onboarding',
        'country'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function store_images()
    {
        return $this->hasMany('App\Models\StoreImage', 'store_id')->select('id', 'store_id', 'store_image');
    }
}
