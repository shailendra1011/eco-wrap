<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $fillable =['user_id','store_id','rating','review'];
    

    public function user_detail()
    {
    	return $this->belongsTo('App\User','user_id','id')->select('id','name','user_image');
    }


}
