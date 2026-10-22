<?php

namespace App\Models;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;

class Chapter extends Model
{
    protected $fillable = ['lesson_id','parent_id','have_child','chapter_no','title','description','status','quiz_id','exercise_id','saved'];

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Chapter','parent_id');
    }
    public function lesson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Lesson','lesson_id','id');
    }

    public function exercise(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Quiz','exercise_id','id');
    }
    public function topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Topic::class,'chapter_id');
    }
    public function quiz(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Quiz::class,'quiz_id','id');
    }
    // public function chapterUser($id){
    //     return $this->hasOne('App\Models\LessonChapterUser','chapter_id')->where('user_id',$id);
    // }
    public function chapterUser($id, $year = null)
    {
        $query = $this->hasOne('App\Models\LessonChapterUser', 'chapter_id')->where('user_id', $id);

        if ($year) {
            // Apply `whereYear` directly to filter by the year of `created_at`
            $query->whereYear('created_at', $year);
        }

        return $query;
    }

}
