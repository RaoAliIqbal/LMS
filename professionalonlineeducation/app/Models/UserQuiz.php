<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuiz extends Model
{
    protected $guarded = [];

    public function quiz(){
        return $this->belongsTo('App\Models\Quiz','quiz_id','id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
