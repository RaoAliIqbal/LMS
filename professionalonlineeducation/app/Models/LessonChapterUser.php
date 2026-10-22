<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Lesson;
use App\Models\Chapter;

class LessonChapterUser extends Model
{
    protected $guarded = [];


    public function updatePer($request,$per){
        $year = (new \App\Services\EducationService())->resolveEnrollmentYear(
            auth()->user()->id,
            $request->courseId,
            $request->year ?? null
        );
        $data = [
            'user_id' => auth()->user()->id,
            'lesson_id' => $request->courseId,
            'chapter_id' => $request->chapterId,
        ];

        $record = $this::where($data)->whereYear('created_at', $year)->first();
        if (!$record) {
            $record = $this::where($data)->orderBy('created_at', 'desc')->first();
        }
        if (!$record) {
            $record = $this::create(array_merge($data, [
                'time_spent' => 0,
                'quiz_percentage' => $per,
                'status' => 1,
            ]));
            return;
        }

        $record->quiz_percentage = $per;
        $record->save();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lesson::class,'lesson_id','id');
    }
    public function chapter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chapter::class,'chapter_id','id');
    }

}
