<?php

namespace App;

use App\Models\Catalog;
use App\Models\Category;
use App\Models\InstructorOfficeTiming;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Instructor extends Authenticatable
{       
    use Notifiable;
    protected $guarded = [];
    
    protected $table = 'instructors';
    protected $guard  = 'instructor';
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }
    
    public function titles(){
        return $this->hasMany(InstructorTitle::class, 'instructor_id', 'id');
    }
    
    public function licenses(){
        return $this->hasMany(InstructorLicense::class, 'instructor_id', 'id');
    }
    

    public function officeTimings(){
        return $this->hasMany(InstructorOfficeTiming::class, 'instructor_id', 'id');
    }
    

    public function catalogs(){
        return $this->belongsToMany(Category::class, 'instructor_catalogs', 'instructor_id', 'category_id')->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Lesson::class, 'course_instructor', 'instructor_id', 'course_id')->withTimestamps();
    }
}
