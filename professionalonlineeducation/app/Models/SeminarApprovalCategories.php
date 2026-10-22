<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CourseApproval;
use App\Models\Category;
use App\Models\Seminar;

class SeminarApprovalCategories extends Model
{
    protected $fillable = ['seminar_approval_id','category_id','status'];

    public function courseApproval(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Seminar::class,'seminar_approval_id');
    }
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
