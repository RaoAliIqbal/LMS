<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Evaluation;
use App\Models\Quiz;
use App\Models\Chapter;
use App\Models\CourseCategory;
use App\Models\CourseState;
use App\Models\CourseApproval;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use F9Web\LaravelDeletable\Traits\RestrictsDeletion;


class Lesson extends Model
{
    use SoftDeletes;
    use RestrictsDeletion;

    public function isDeletable() : bool
    {
        return false;
    }
    protected $fillable = [
        'title','title_two','title_dev','image','status','quiz_id','evaluation_id',
        'description','video','pdf_file','price','hours','tag','sort_by',
        // Zoom live session fields
        'has_zoom','zoom_link','zoom_meeting_id','zoom_password','zoom_date','zoom_time','zoom_description',
    ];

    public function chapters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Chapter::class,'lesson_id','id')->orderBy('chapter_no','asc');
    }
    public function msinChapters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Chapter::class,'lesson_id','id')->where('parent_id','0')->orderBy('chapter_no','asc');
    }
    public function randomMainChapters(){
        return $this->hasMany(Chapter::class,'lesson_id','id')->where('parent_id','0')->inRandomOrder()->limit(3);
    }
    public function quiz(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Quiz::class,'quiz_id','id');
    }
    public function evaluation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Evaluation::class,'evaluation_id','id');
    }
    public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseCategory::class,'course_id','id');
    }
    public function allCategories()
    {
        return $this->belongsToMany('App\Models\Category','course_categories','category_id','course_id');
    }
    public function states(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseState::class,'course_id','id');
    }
    public function approvals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseApproval::class,'lesson_id','id');
    }

    public function Users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'lesson_users');
    }



}
