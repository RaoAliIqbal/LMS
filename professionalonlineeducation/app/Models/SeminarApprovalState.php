<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CourseApproval;
use App\Models\State;
use App\Models\Seminar;

class SeminarApprovalState extends Model
{
    protected $fillable = ['seminar_approval_id','state_id','status'];

    public function courseApproval(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Seminar::class,'seminar_approval_id');
    }
    public function state(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(State::class,'state_id');
    }
}
