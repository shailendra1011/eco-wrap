<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReferral extends Model
{
    public function referred_to_user_data()
    {
        return $this->hasOne(\App\User::class,'id','referred_to_user_id');
    }
}
