<?php

namespace App\Models;

use App\Models\CourseApprovalState;
use Illuminate\Database\Eloquent\Model;
use App\Models\State;
use App\Models\CourseApprovalCategory;
use App\Models\Approval;
use App\Models\Lesson;

class CourseApproval extends Model
{
    protected $fillable = ['approval_id','lesson_id','approval_no','provider_no','start_date','end_date','status'];

    public function States(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseApprovalState::class,'course_approval_id');
    }

    public function Categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseApprovalCategory::class,'course_approval_id');
    }
    public function approval(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

}
