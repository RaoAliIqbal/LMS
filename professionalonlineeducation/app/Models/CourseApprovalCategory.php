<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CourseApproval;
use App\Models\Category;

class CourseApprovalCategory extends Model
{
    protected $fillable = ['course_approval_id','category_id','status'];

    public function courseApproval(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseApproval::class,'course_approval_id');
    }
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
