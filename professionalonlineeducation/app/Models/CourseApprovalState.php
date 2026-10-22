<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CourseApproval;
use App\Models\State;

class CourseApprovalState extends Model
{
    protected $fillable = ['course_approval_id','state_id','status'];

    public function courseApproval(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseApproval::class,'course_approval_id');
    }
    public function state(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(State::class,'state_id');
    }
}
