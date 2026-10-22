<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EvaluationEmail;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\UserEvaluation;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EvaluationMailTestController extends Controller
{
    public function send()
    {
        try {
            foreach (UserEvaluation::orderBy('id', 'desc')->cursor() as $evaluation) {
                $user = User::find($evaluation->user_id);
                $lesson = Lesson::find($evaluation->lesson_id);
                if (!$user || !$lesson || !$evaluation->created_at) {
                    continue;
                }

                $details = json_decode($evaluation->details, true);
                if (!is_array($details) || !$details) {
                    continue;
                }

                $enrollment = LessonUser::where('user_id', $user->id)
                    ->where('lesson_id', $lesson->id)
                    ->whereYear('created_at', $evaluation->created_at->format('Y'))
                    ->orderBy('created_at', 'desc')->first();
                if (!$enrollment) {
                    continue;
                }

                Mail::to('drcanham@professionalonlineeducation.com')->send(new EvaluationEmail([
                    'request' => $evaluation,
                    'user' => $user,
                    'lesson' => $lesson,
                    'userLesson' => $enrollment,
                ]));

                return response()->json([
                    'message' => 'One evaluation email submitted. Check the drcanham inbox to confirm delivery.',
                    'recipient' => 'drcanham@professionalonlineeducation.com',
                    'evaluation_id' => $evaluation->id,
                    'course_id' => $lesson->id,
                ])->header('Cache-Control', 'no-store');
            }

            return response()->json([
                'message' => 'No saved evaluation with a matching user, course and enrollment found. No email sent.',
            ], 404)->header('Cache-Control', 'no-store');
        } catch (\Throwable $e) {
            Log::error('Evaluation email test failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Evaluation email test failed. Check the application log for the error.',
            ], 500)->header('Cache-Control', 'no-store');
        }
    }
}