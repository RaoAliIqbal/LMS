<?php

namespace App\Models;

use App\Models\SeminarApprovalState;
use Illuminate\Database\Eloquent\Model;
use App\Models\SeminarApprovalCategories;
use App\Models\Approval;
use App\Models\Seminar;

class SeminarApproval extends Model
{
    protected $fillable = ['approval_id','seminar_id','approval_no','provider_no','start_date','end_date','status'];

    public function States(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SeminarApprovalState::class,'seminar_approval_id');
    }

    public function Categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SeminarApprovalCategories::class,'seminar_approval_id');
    }
    public function approval(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }
    public function seminar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Seminar::class, 'seminar_id');
    }

}
