<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable =['user_id','country_code','mobile','name','house_no','address','latitude','longitude','city','pin_code','house_type','status'];

}
