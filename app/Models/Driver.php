<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable
{
    use Notifiable, HasApiTokens;
    protected $fillable = [
        'driver_name', 'driver_name_es', 'city', 'country', 'isSubscribed', 'driver_email', 'driver_password', 'driver_country_code', 'driver_image', 'driver_mobile', 'social_media_type', 'google_id', 'apple_id', 'facebook_id', 'driver_lattitude', 'driver_longitude', 'vechile_type', 'vechile_number', 'driver_address', 'vechile_registration', 'driving_license','device_token'
    ];

    public function getDriverImageAttribute($image)
    {
        return $image != null ? url($image) : '';
    }
    public function getDrivingLicenseAttribute($license)
    {
        return $license != null ? url($license) : '';
    }
    public function getvehicleRegistrationAttribute($registration)
    {
        return $registration != null ? url($registration) : '';
    }
}
