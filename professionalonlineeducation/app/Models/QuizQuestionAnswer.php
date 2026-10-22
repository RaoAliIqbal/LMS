<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestionAnswer extends Model
{
    protected $fillable = ['question_id','answer','answer_two','is_true','status'];

    public function question(){
        return $this->belongsTo('App\Models\QuizQuestion','question_id','id');
    }
}
