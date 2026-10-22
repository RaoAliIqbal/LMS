<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quiz;

class Topic extends Model
{
    protected $fillable = ['lesson_id','chapter_id','title','slug','description','status','exercise_id'];
    public function exercise(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Quiz::class,'exercise_id','id');
    }
}
