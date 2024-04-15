<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserReserveTable extends Model
{
    protected $fillable = ['user_id', 'store_id', 'reservation_type', 'time', 'date', 'no_of_persons', 'booking_person_name', 'country_code', 'booking_person_mobile', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
