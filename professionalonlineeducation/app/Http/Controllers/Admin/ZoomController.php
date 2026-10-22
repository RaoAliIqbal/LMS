<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;
use App\Services\ZoomService;
use App\Support\AdminDbLoader;
use App\Mail\ZoomSessionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ZoomController extends Controller
{
    protected $zoom;

    public function __construct()
    {
        $this->zoom = new ZoomService();
    }

    /*
    |--------------------------------------------------------------------------
    | TEST CONNECTION
    |--------------------------------------------------------------------------
    */
    public function testConnection()
    {
        $result = $this->zoom->testConnection();
        return response()->json($result);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE MEETING  →  saves details to lessons table
    |--------------------------------------------------------------------------
    */
    public function createMeeting(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|integer|exists:lessons,id',
            'topic'     => 'required|string|max:200',
            'zoom_date' => 'required|date',
            'zoom_time' => 'required',
            'duration'  => 'required|integer|min:15|max:480',
            'timezone'  => 'nullable|string',
            'agenda'    => 'nullable|string|max:1000',
        ]);

        $result = $this->zoom->createMeeting([
            'topic'     => $request->topic,
            'zoom_date' => $request->zoom_date,
            'zoom_time' => $request->zoom_time,
            'duration'  => $request->duration,
            'timezone'  => $request->timezone ?? 'America/Los_Angeles',
            'agenda'    => $request->agenda ?? '',
        ]);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        // Save meeting details to lessons table
        $lesson = Lesson::find($request->lesson_id);
        $lesson->has_zoom        = 1;
        $lesson->zoom_link       = $result['zoom_link'];
        $lesson->zoom_meeting_id = $result['meeting_id'];
        $lesson->zoom_password   = $result['zoom_password'];
        $lesson->zoom_date       = $request->zoom_date;
        $lesson->zoom_time       = $request->zoom_time;
        $lesson->zoom_description = $request->agenda ?? '';
        $lesson->save();

        // ── Notify all enrolled students ──────────────────────
        $this->notifyEnrolledStudents($lesson, [
            'meeting_id'       => $result['meeting_id'],
            'zoom_link'        => $result['zoom_link'],
            'zoom_password'    => $result['zoom_password'],
            'zoom_date'        => $request->zoom_date,
            'zoom_time'        => $request->zoom_time,
            'zoom_description' => $request->agenda ?? '',
        ]);

        return response()->json([
            'success'        => true,
            'message'        => 'Zoom meeting created and students notified by email!',
            'meeting_id'     => $result['meeting_id'],
            'zoom_link'      => $result['zoom_link'],
            'start_url'      => $result['start_url'],
            'zoom_password'  => $result['zoom_password'],
            'topic'          => $result['topic'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE MEETING  →  removes from Zoom + clears lesson fields
    |--------------------------------------------------------------------------
    */
    public function deleteMeeting(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        if ($lesson->zoom_meeting_id) {
            $this->zoom->deleteMeeting($lesson->zoom_meeting_id);
        }

        // Clear zoom fields from lesson
        $lesson->has_zoom        = 0;
        $lesson->zoom_link       = null;
        $lesson->zoom_meeting_id = null;
        $lesson->zoom_password   = null;
        $lesson->zoom_date       = null;
        $lesson->zoom_time       = null;
        $lesson->zoom_description = null;
        $lesson->save();

        return response()->json([
            'success' => true,
            'message' => 'Zoom meeting deleted and removed from course.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE MEETING  →  syncs changes to Zoom
    |--------------------------------------------------------------------------
    */
    public function updateMeeting(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        if (!$lesson->zoom_meeting_id) {
            return response()->json(['success' => false, 'message' => 'No Zoom meeting linked to this course.'], 422);
        }

        $result = $this->zoom->updateMeeting($lesson->zoom_meeting_id, [
            'topic'     => $request->topic ?? $lesson->title,
            'zoom_date' => $request->zoom_date ?? $lesson->zoom_date,
            'zoom_time' => $request->zoom_time ?? $lesson->zoom_time,
            'duration'  => $request->duration ?? 60,
            'agenda'    => $request->agenda ?? $lesson->zoom_description,
        ]);

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        // Update lesson fields
        if ($request->zoom_date)  $lesson->zoom_date        = $request->zoom_date;
        if ($request->zoom_time)  $lesson->zoom_time        = $request->zoom_time;
        if ($request->agenda)     $lesson->zoom_description = $request->agenda;
        $lesson->save();

        // ── Notify enrolled students of updated details ────────
        $this->notifyEnrolledStudents($lesson, [
            'meeting_id'       => $lesson->zoom_meeting_id,
            'zoom_link'        => $lesson->zoom_link,
            'zoom_password'    => $lesson->zoom_password,
            'zoom_date'        => $lesson->zoom_date,
            'zoom_time'        => $lesson->zoom_time,
            'zoom_description' => $lesson->zoom_description,
        ]);

        return response()->json(['success' => true, 'message' => 'Meeting updated and students notified!']);
    }

    /*
    |--------------------------------------------------------------------------
    | GET MEETING STATUS  →  live status from Zoom API
    |--------------------------------------------------------------------------
    */
    public function getMeetingStatus($meetingId)
    {
        $result = $this->zoom->getMeeting($meetingId);
        return response()->json($result);
    }

    /*
    |--------------------------------------------------------------------------
    | MEETINGS DASHBOARD  →  admin page showing all Zoom meetings
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $lessons = collect();
        $zoomList = ['meetings' => []];

        try {
            if (AdminDbLoader::hasTable('lessons')) {
                $query = Lesson::query();
                if (\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'has_zoom')) {
                    $query->where('has_zoom', 1);
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'zoom_meeting_id')) {
                    $query->whereNotNull('zoom_meeting_id');
                }
                $lessons = $query->get();
            }
        } catch (\Throwable $e) {
            \Log::error('admin zoom index lessons failed', ['message' => $e->getMessage()]);
        }

        try {
            $zoomList = $this->zoom->listMeetings();
        } catch (\Throwable $e) {
            \Log::error('admin zoom listMeetings failed', ['message' => $e->getMessage()]);
        }

        return view('admin.zoom.index', [
            'lessons' => $lessons,
            'zoomMeetings' => $zoomList['meetings'] ?? [],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER — Send Zoom notification to all enrolled students
    |--------------------------------------------------------------------------
    */
    protected function notifyEnrolledStudents(Lesson $lesson, array $meetingData)
    {
        try {
            // Get all enrolled (not completed + hidden) students for this course
            $enrolledUsers = LessonUser::where('lesson_id', $lesson->id)
                ->where(function($q) {
                    $q->whereNull('completed_at')
                      ->orWhere('is_show', '!=', 0);
                })
                ->with('user')
                ->get();

            $sentCount = 0;
            foreach ($enrolledUsers as $enrollment) {
                $user = $enrollment->user;
                if (!$user || !$user->email) continue;

                try {
                    Mail::to($user->email)
                        ->send(new ZoomSessionNotification($lesson, $user->name, $meetingData));
                    $sentCount++;
                } catch (\Exception $e) {
                    \Log::error("Zoom email failed for user {$user->id}: " . $e->getMessage());
                }
            }

            \Log::info("Zoom notification sent to {$sentCount} students for lesson {$lesson->id}");

        } catch (\Exception $e) {
            \Log::error('notifyEnrolledStudents failed: ' . $e->getMessage());
        }
    }
}
