<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizQuestion;

class Quiz extends Model
{
    protected $fillable = ['title','description','passing_percentage','status','type'];

    public function questions(){
        return $this->hasMany(QuizQuestion::class,'quiz_id','id');
    }

    public function userAnswers($userId){
        return $this->hasMany('App\Models\UserQuizAnswer','quiz_id')->where('user_id', $userId);
    }
}
