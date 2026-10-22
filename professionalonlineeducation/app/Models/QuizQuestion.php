<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = ['quiz_id','question','mark','type','status'];

    public function answers(){
        return $this->hasMany('App\Models\QuizQuestionAnswer','question_id','id');
    }
    public function CorrectAnswers(){
        return $this->hasMany('App\Models\QuizQuestionAnswer','question_id','id')->where('is_true','1');
    }
    public function quiz(){
        return $this->belongsTo('App\Models\Quiz','quiz_id','id');
    }
}
