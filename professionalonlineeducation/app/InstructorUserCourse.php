<?php

namespace App;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Model;

class InstructorUserCourse extends Model
{
    protected $table = 'instructor_user_courses';

    public function instructor(){
        return $this->belongsTo(Instructor::class, 'instructor_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Lesson::class, 'course_id', 'id');
    }
}
