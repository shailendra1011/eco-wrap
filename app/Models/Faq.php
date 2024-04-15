<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Language;

class Faq extends Model
{

    protected $with     =   ['language'];

    public function language()
    {
        return $this->belongsTo(Language::class,'language','language_code');
    }
}
