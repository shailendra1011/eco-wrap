<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class HelpAndSupport extends Model
{
    protected $fillable = [
        'name','mobile','comment','user_id'
    ];

    protected $with  =   ['user_data'];

    public function user_data(){
        return  $this->belongsTo(\App\User::class,'user_id')->select('id','name','email');
    }
}
