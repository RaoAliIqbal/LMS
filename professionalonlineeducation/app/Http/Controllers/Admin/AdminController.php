<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\LessonChapterUser;
use App\Models\UserQuiz;
use App\Models\UserEvaluation;
use App\Models\State;
use App\Models\PaymentLogs;
use App\Models\UserClinic;
use App\Project;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Services\AdminService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\MailTemplate;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Support\AdminEnrollmentHelper;

class AdminController extends Controller
{

    public function adminLogin(){
        session(['staff_login' => true]);
        session()->forget('url.intended');
        return view('admin/login');
    }

    public function analytics(Request $request){
        $year = $request->get('year', date('Y'));

        // ── KPI Cards ──────────────────────────────────────────────
        $totalStudents      = User::count();
        $totalCourses       = Lesson::count();
        $totalEnrollments   = LessonUser::whereYear('created_at', $year)->count();
        $totalCompleted     = LessonUser::whereYear('created_at', $year)->whereNotNull('completed_at')->count();
        $overallCompletion  = $totalEnrollments > 0 ? round(($totalCompleted / $totalEnrollments) * 100, 1) : 0;
        $newStudentsYear    = User::whereYear('created_at', $year)->count();

        // ── Monthly Enrollments (current year) ────────────────────
        $monthlyEnrollments = LessonUser::whereYear('created_at', $year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('count', 'month');

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = $monthlyEnrollments->get($m, 0);
        }

        // ── Monthly Completions (current year) ────────────────────
        $monthlyCompletions = LessonUser::whereYear('created_at', $year)
            ->whereNotNull('completed_at')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('count', 'month');

        $monthlyCompData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyCompData[] = $monthlyCompletions->get($m, 0);
        }

        // ── Course Completion Rates ────────────────────────────────
        $courseStats = DB::select("
            SELECT l.id, l.title, l.hours,
                COUNT(lu.id) as enrollments,
                SUM(CASE WHEN lu.completed_at IS NOT NULL THEN 1 ELSE 0 END) as completions,
                ROUND(SUM(CASE WHEN lu.completed_at IS NOT NULL THEN 1 ELSE 0 END)*100.0/COUNT(lu.id),1) as completion_rate
            FROM lesson_users lu
            JOIN lessons l ON l.id = lu.lesson_id
            WHERE YEAR(lu.created_at) = ?
            GROUP BY lu.lesson_id, l.id, l.title, l.hours
            ORDER BY enrollments DESC
        ", [$year]);

        // ── Avg Time Spent Per Course ──────────────────────────────
        $timeStats = DB::select("
            SELECT l.title, l.hours,
                COUNT(DISTINCT lu.user_id) as students,
                ROUND(SUM(lcu.time_spent) / 60, 1) as total_hours_spent,
                ROUND(AVG(lcu.time_spent), 1) as avg_mins_per_chapter
            FROM lesson_users lu
            JOIN lessons l ON l.id = lu.lesson_id
            JOIN lesson_chapter_users lcu ON lcu.lesson_id = lu.lesson_id AND lcu.user_id = lu.user_id
            WHERE YEAR(lu.created_at) = ?
            GROUP BY lu.lesson_id, l.title, l.hours
            ORDER BY students DESC
            LIMIT 10
        ", [$year]);

        // ── Quiz Performance ───────────────────────────────────────
        $quizStats = DB::select("
            SELECT l.title,
                ROUND(AVG(uq.user_percentage),1) as avg_score,
                COUNT(uq.id) as total_attempts,
                SUM(CASE WHEN uq.user_percentage >= 75 THEN 1 ELSE 0 END) as passes,
                ROUND(SUM(CASE WHEN uq.user_percentage >= 75 THEN 1 ELSE 0 END)*100.0/COUNT(uq.id),1) as pass_rate
            FROM user_quizzes uq
            JOIN lessons l ON l.id = uq.lesson_id
            WHERE YEAR(uq.created_at) = ?
            GROUP BY uq.lesson_id, l.title
            ORDER BY total_attempts DESC
            LIMIT 10
        ", [$year]);

        // ── Top Students (most courses completed) ─────────────────
        $topStudents = DB::select("
            SELECT u.id, u.first_name, u.last_name, u.email,
                COUNT(lu.id) as courses_completed,
                ROUND(AVG(uq.user_percentage),1) as avg_quiz_score
            FROM users u
            JOIN lesson_users lu ON lu.user_id = u.id AND lu.completed_at IS NOT NULL AND YEAR(lu.created_at) = ?
            LEFT JOIN user_quizzes uq ON uq.user_id = u.id AND YEAR(uq.created_at) = ?
            GROUP BY u.id, u.first_name, u.last_name, u.email
            ORDER BY courses_completed DESC
            LIMIT 10
        ", [$year, $year]);

        // ── Available years for filter ─────────────────────────────
        $years = LessonUser::select(DB::raw('YEAR(created_at) as y'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('y', 'desc')
            ->pluck('y');

        return view('admin.analytics', compact(
            'year', 'years',
            'totalStudents', 'totalCourses', 'totalEnrollments',
            'totalCompleted', 'overallCompletion', 'newStudentsYear',
            'monthlyData', 'monthlyCompData',
            'courseStats', 'timeStats', 'quizStats', 'topStudents'
        ));
    }
    public function dashboard(){
        try {
            $clients = count(User::role('User')->get());
        } catch (\Throwable $e) {
            \Log::error('admin dashboard: role query failed', ['error' => $e->getMessage()]);
            $clients = (int) User::count();
        }

        try {
            $courses = (int) Lesson::count();
        } catch (\Throwable $e) {
            \Log::error('admin dashboard: lessons count failed', ['error' => $e->getMessage()]);
            $courses = 0;
        }

        return view('admin.dashboard', compact('clients', 'courses'));
    }

    public function profile(){
        $users = User::find(Auth::user()->id);
        return view('admin.profile' , compact('users'));

    }

    public function UpdateProfile(Request $request){
        $service = new AdminService();
        $updateProfile = $service->UpdateProfile($request);
        if ($updateProfile['type'] == 'error'){
            return Redirect::to(route('admin_profile'))->with('error', $updateProfile['msg']);
        }else if($updateProfile['type'] == 'success'){
            return Redirect::to(route('admin_profile'))->with('info', $updateProfile['msg']);
        }
    }

    public function UpdateUserPassword(Request $request){
        $service = new AdminService();
        $updateProfile = $service->UpdateUserPwd($request);
        if ($updateProfile['type'] == 'error'){
            return Redirect::back()->with('error', $updateProfile['msg']);
        }else if($updateProfile['type'] == 'success'){
            return Redirect::back()->with('info', $updateProfile['msg']);
        }
    }



    public function clients(Request $request){
        $UserModel = new User();
        if ($request->course_id){
            $lesson = Lesson::find($request->course_id);
            if ($lesson){
                $clients = $lesson->Users;
            }else{
                $clients = [];
            }
        }else{
            try {
                $clients = $UserModel::role('User')->get();
            } catch (\Throwable $e) {
                \Log::error('admin clients: role query failed', ['error' => $e->getMessage()]);
                $clients = User::orderBy('created_at', 'desc')->limit(2000)->get();
            }
        }

        try {
            $lessons = Lesson::orderBy('title')->get(['id', 'title']);
        } catch (\Throwable $e) {
            \Log::error('admin clients: could not load courses', ['error' => $e->getMessage()]);
            $lessons = collect();
        }

        $pageData = ['title'=>"Customer's List"];
        return view('admin.clients',compact('clients','pageData','lessons','request'));
    }

    public function clientDetails($id){
        $UserModel = new User();
        $client = $UserModel::find($id);
        $states = State::all();
        return view('admin.clientView',compact('client','states'));
    }
    public function clientDetailsView($id){
        $UserModel = new User();
        $client = $UserModel::find($id);
        $states = State::all();
        return view('admin.clientDetailsView',compact('client','states'));
    }
    public function clientCourses($id){
        $UserModel = new User();
        $client = $UserModel::find($id);
        if (!$client) {
            return Redirect::back()->with('error', 'Student not found.');
        }
        // Show only the latest enrollment per course — prevents old re-enrollment records showing wrong dates
        $latestIds = LessonUser::where('user_id', $client->id)
            ->selectRaw('MAX(id) as id')
            ->groupBy('lesson_id')
            ->pluck('id');
        $courses = LessonUser::whereIn('id', $latestIds)->orderBy('created_at','desc')->get();
        try {
            $lessons = Lesson::orderBy('title')->get(['id', 'title']);
        } catch (\Throwable $e) {
            \Log::error('admin clientCourses: could not load courses', ['error' => $e->getMessage()]);
            $lessons = collect();
        }
        $enrolledLessonIds = $courses->pluck('lesson_id')->toArray();
        $courseCodes = $this->courseCodeMap();
        return view('admin.clientCourses',compact('client','courses','lessons','enrolledLessonIds','courseCodes'));
    }
    public function clientActivity($id){
        $client = User::find($id);
        $educationService = new \App\Services\EducationService();

        // All enrollments grouped by course, most recent first
        $enrollments = LessonUser::where('user_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('lesson_id');

        $activityLog = [];

        foreach ($enrollments as $lessonId => $records) {
            $course = Lesson::find($lessonId);
            if (!$course) continue;

            foreach ($records as $enrollment) {
                $year = \Carbon\Carbon::parse($enrollment->created_at)->year;

                $chapters = $educationService->getUserLessonChapters($client->id, $lessonId, $year)
                    ->sortBy(function ($ch) {
                        return optional($ch->chapter)->chapter_no ?? $ch->chapter_id;
                    })
                    ->values();

                // Quiz attempts for this enrollment year
                $quizzes = UserQuiz::where('user_id', $client->id)
                    ->where('lesson_id', $lessonId)
                    ->whereYear('created_at', $year)
                    ->orderBy('created_at', 'asc')
                    ->get();

                if ($quizzes->isEmpty()) {
                    $quizzes = UserQuiz::where('user_id', $client->id)
                        ->where('lesson_id', $lessonId)
                        ->orderBy('created_at', 'asc')
                        ->get();
                }

                // Evaluation submission for this enrollment year
                $evaluation = UserEvaluation::where('user_id', $client->id)
                    ->where('lesson_id', $lessonId)
                    ->whereYear('created_at', $year)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Total time spent (in minutes)
                $totalMinutes = $chapters->sum(function($c){ return (float)$c->time_spent; });

                // Average quiz score
                $avgScore = $quizzes->count() > 0
                    ? round($quizzes->avg('user_percentage'), 1)
                    : null;

                $activityLog[] = [
                    'course'       => $course,
                    'enrollment'   => $enrollment,
                    'year'         => $year,
                    'chapters'     => $chapters,
                    'quizzes'      => $quizzes,
                    'evaluation'   => $evaluation,
                    'totalMinutes' => $totalMinutes,
                    'avgScore'     => $avgScore,
                    'isLatest'     => ($records->first()->id === $enrollment->id),
                ];
            }
        }

        return view('admin.clientActivity', compact('client', 'activityLog'));
    }

    public function clientPaymentLogs($id){
        $UserModel = new User();
        $client = $UserModel::find($id);
        if (!$client) {
            return Redirect::back()->with('error', 'Student not found.');
        }
        $logs = $client->paymentLogs()->orderBy('id','desc')->get();
        $paymentService = new PaymentService();
        $paymentLogs = $paymentService->addCourses($logs);
        $enrolledLessonIds = LessonUser::where('user_id', $client->id)
            ->whereYear('created_at', date('Y'))
            ->pluck('lesson_id')
            ->toArray();

        return view('admin.clientPaymentLogs',compact('client','paymentLogs','enrolledLessonIds'));
    }
    public function clientLicences($id){
        $UserModel = new User();
        $client = $UserModel::find($id);
        $licences = $client->linceses()->orderBy('id','desc')->get();

        return view('admin.clientLicences',compact('client','licences'));
    }

    public function lessonUsers($id){
        $lesson = Lesson::find($id);
        $clients = $lesson->Users;
        $pageData = ['title'=>$lesson->title."'s Users"];
        return view('admin.clients',compact('clients','pageData')   );

    }





    public function notifications(){
        $MailModel = new MailTemplate();
        $mails = $MailModel::all();
        return view('admin.mails',compact('mails'));

    }

    public function notificationDetails($id){
        $MailModel = new MailTemplate();
        $mail = $MailModel::find($id);
        return view('admin.mailView',compact('mail'));
    }

    public function UpdateNotification(Request $request){
        $service = new AdminService();
        $updateProject = $service->updateNotification($request);
        if ($updateProject['type'] == 'error'){
            return Redirect::back()->with('error', $updateProject['msg']);
        }else if($updateProject['type'] == 'success'){
            return Redirect::back()->with('info', $updateProject['msg']);
        }
    }


    public function UpdateUser(Request $request){

        $service = new AdminService();
        $updateProfile = $service->UpdateProfile($request,$request->id);
        if ($updateProfile['type'] == 'error'){
            return Redirect::back()->with('error', $updateProfile['msg']);
        }else if($updateProfile['type'] == 'success'){
            return Redirect::back()->with('info', $updateProfile['msg']);
        }
    }

    public function clientDelete($id){
        $service = new AdminService();
        $updateProfile = $service->userDelete($id);
        if ($updateProfile['type'] === 'error'){
            return Redirect::back()->with('error', $updateProfile['msg']);
        }else if($updateProfile['type'] === 'success'){
            return Redirect::back()->with('info', $updateProfile['msg']);
        }
    }
    public function clientDeactivate($id){
        $service = new AdminService();
        $updateProfile = $service->changeUserStatus($id,0);
        if ($updateProfile['type'] === 'error'){
            return Redirect::back()->with('error', $updateProfile['msg']);
        }else if($updateProfile['type'] === 'success'){
            return Redirect::back()->with('info', $updateProfile['msg']);
        }
    }
    public function clientActivate($id){
        $service = new AdminService();
        $updateProfile = $service->changeUserStatus($id,1);
        if ($updateProfile['type'] === 'error'){
            return Redirect::back()->with('error', $updateProfile['msg']);
        }else if($updateProfile['type'] === 'success'){
            return Redirect::back()->with('info', $updateProfile['msg']);
        }
    }

    public function deleteClientCourse($lesson_id, $user_id){
       try{
            \DB::transaction(function () use ($lesson_id, $user_id) {
                LessonUser::where('lesson_id', $lesson_id)->where('user_id', $user_id)->delete();   
                LessonChapterUser::where('lesson_id', $lesson_id)->where('user_id', $user_id)->delete();  
            });
            return Redirect::back()->with('success', 'User course removed successfully');
       }catch(Exception $e){
            return Redirect::back()->with('error', 'Some error occured try again');
       }
    }

    public function toggleClientCourse($lesson_id, $user_id, $is_show){
        LessonUser::where(['lesson_id'=>$lesson_id, 'user_id'=>$user_id])->update(['is_show'=>intval($is_show)]);
        return Redirect::back()->with('success', "Client course apperance updated successfully");
    }

    /**
     * Resend the course-completion notification email for a specific lesson_user record.
     * Useful when the original email contained wrong dates (e.g. pulled from an old re-enrollment row).
     */
    public function resendCourseCompletion($userId, $lessonUserId)
    {
        $lessonUser = LessonUser::find($lessonUserId);

        if (!$lessonUser || (int)$lessonUser->user_id !== (int)$userId) {
            return Redirect::back()->with('error', 'Record not found or user mismatch.');
        }

        if (!$lessonUser->completed_at) {
            return Redirect::back()->with('error', 'This course has not been completed yet — cannot resend completion email.');
        }

        $year = \Carbon\Carbon::parse($lessonUser->created_at)->year;

        try {
            $hasEvaluation = (new \App\Services\CompletionReportService())->resend($lessonUser);
            if (in_array(config('mail.default'), ['log', 'array'], true)) {
                return Redirect::back()->with('info', 'Reports generated using the test mail driver; no external email was sent.' . ($hasEvaluation ? '' : ' No saved evaluation was found for this enrollment.'));
            }
            return Redirect::back()->with($hasEvaluation ? 'success' : 'info',
                $hasEvaluation ? 'Completion report and saved evaluation sent to the administrator.'
                : 'Completion report sent to the administrator. No saved evaluation exists for this enrollment.');

        } catch (\Throwable $e) {
            \Log::error('resendCourseCompletion failed', [
                'lesson_user_id' => $lessonUserId,
                'user_id'        => $userId,
                'error'          => $e->getMessage(),
            ]);
            return Redirect::back()->with('error', 'Failed to resend email: ' . $e->getMessage());
        }
    }

    public function grantClientCourse(Request $request, $id)
    {
        $request->validate([
            'lesson_id' => 'required|integer|exists:lessons,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        $client = User::find($id);
        if (!$client) {
            return Redirect::back()->with('error', 'Student not found.');
        }

        $result = AdminEnrollmentHelper::enrollUserInCourse((int) $client->id, (int) $request->lesson_id, [
            'price' => $request->filled('price') ? (float) $request->price : 0,
        ]);

        if (($result['type'] ?? '') === 'success') {
            return Redirect::back()->with('success', $result['msg']);
        }

        return Redirect::back()->with('error', $result['msg'] ?? 'Could not add course to this student.');
    }

    public function updateClientChapterTime(Request $request, $id)
    {
        $request->validate([
            'lesson_id' => 'required|integer|exists:lessons,id',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'year' => 'required|integer|min:2020|max:2100',
            'time_spent' => 'required|numeric|min:0|max:999',
            'mark_quiz_passed' => 'nullable|boolean',
        ]);

        $client = User::find($id);
        if (! $client) {
            return Redirect::back()->with('error', 'Student not found.');
        }

        $service = new \App\Services\EducationService();
        $result = $service->adminUpdateChapterProgress(
            (int) $client->id,
            (int) $request->lesson_id,
            (int) $request->chapter_id,
            (int) $request->year,
            (float) $request->time_spent,
            $request->boolean('mark_quiz_passed')
        );

        if (($result['type'] ?? '') === 'success') {
            return Redirect::back()->with('success', $result['msg']);
        }

        return Redirect::back()->with('error', $result['msg'] ?? 'Could not update chapter progress.');
    }

    public function enrollFromPayment(Request $request, $userId, $logId)
    {
        try {
            $request->validate([
                'lesson_id' => 'required|integer|exists:lessons,id',
            ]);

            $client = User::find($userId);
            $log = PaymentLogs::where('id', $logId)->where('user_id', $userId)->first();
            if (! $client || ! $log) {
                return Redirect::back()->with('error', 'Payment record not found for this student.');
            }

            $paymentService = new PaymentService();
            [$lessonIds] = $paymentService->parseLessonAndSeminarIds($log);
            if (! in_array((int) $request->lesson_id, $lessonIds, true)) {
                return Redirect::back()->with('error', 'This course was not part of that payment.');
            }

            $course = Lesson::find($request->lesson_id);
            $result = AdminEnrollmentHelper::enrollUserInCourse((int) $client->id, (int) $request->lesson_id, [
                'price' => $course ? $course->price : 0,
            ]);

            if (($result['type'] ?? '') === 'success') {
                return Redirect::back()->with('success', 'Course added from payment record: '.$result['msg']);
            }

            return Redirect::back()->with('error', $result['msg'] ?? 'Could not enroll student from payment.');
        } catch (\Throwable $e) {
            \Log::error('enrollFromPayment failed', [
                'user_id' => $userId,
                'log_id' => $logId,
                'lesson_id' => $request->input('lesson_id'),
                'error' => $e->getMessage(),
            ]);

            return Redirect::back()->with('error', 'Could not enroll from payment: '.$e->getMessage());
        }
    }

    public function createClientForm()
    {
        try {
            $lessons = Lesson::orderBy('title')->get(['id', 'title', 'price']);
        } catch (\Throwable $e) {
            \Log::error('createClientForm: could not load courses', ['error' => $e->getMessage()]);
            $lessons = collect();
        }
        $states = State::all();
        $courseCodes = $this->courseCodeMap();

        return view('admin.createClient', compact('lessons', 'states', 'courseCodes'));
    }

    public function storeClientWithCourse(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:40',
            'state' => 'required|string|max:120',
            'city' => 'required|string|max:120',
            'zipcode' => 'required|string|max:30',
            'address' => 'nullable|string|max:1000',
            'clinic_name' => 'nullable|string|max:150',
            'lesson_id' => 'required|integer|exists:lessons,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => '',
                'last_name' => $request->last_name,
                'name' => trim($request->first_name.' '.$request->last_name),
                'phone' => $request->phone,
                'address' => $request->address ?? '123 Main St',
                'email' => $request->email,
                'state' => $request->state,
                'city' => $request->city,
                'zipcode' => $request->zipcode,
                'category_id' => 2,
                'password' => Hash::make($request->password),
                'real_password' => $request->password,
                'status' => 1,
            ]);

            try {
                $user->assignRole(2);
            } catch (\Throwable $e) {
                \Log::warning('storeClientWithCourse assignRole: '.$e->getMessage());
            }

            UserClinic::create([
                'user_id' => $user->id,
                'clinic_name' => $request->clinic_name ?: trim($request->first_name.' '.$request->last_name.' Clinic'),
                'office_phone' => $request->phone,
                'address' => $request->address ?? '123 Main St',
                'city' => $request->city,
                'state' => $request->state,
                'zipcode' => $request->zipcode,
            ]);

            $course = Lesson::find($request->lesson_id);
            $result = AdminEnrollmentHelper::enrollUserInCourse((int) $user->id, (int) $request->lesson_id, [
                'price' => $request->filled('price') ? (float) $request->price : ($course ? $course->price : 0),
            ]);

            if (($result['type'] ?? '') !== 'success') {
                return Redirect::route('admin_clientCourses', $user->id)
                    ->with('error', 'Account created but course failed: '.($result['msg'] ?? 'unknown'));
            }

            return Redirect::route('admin_clientCourses', $user->id)
                ->with('success', "Student {$user->email} created and course added successfully.");
        } catch (\Throwable $e) {
            \Log::error('storeClientWithCourse failed', ['error' => $e->getMessage(), 'email' => $request->email]);
            return Redirect::back()->withInput()->with('error', 'Could not create student: '.$e->getMessage());
        }
    }

    public function orphanPayments()
    {
        $paymentService = new PaymentService();
        $orphans = [];

        $logs = PaymentLogs::with([])->orderBy('id', 'desc')->limit(200)->get();
        foreach ($logs as $log) {
            [$lessonIds] = $paymentService->parseLessonAndSeminarIds($log);
            if (!count($lessonIds) || !$log->user_id) {
                continue;
            }
            $user = User::find($log->user_id);
            $missing = [];
            foreach ($lessonIds as $lid) {
                $enrolled = LessonUser::where('user_id', $log->user_id)
                    ->where('lesson_id', $lid)
                    ->whereYear('created_at', date('Y'))
                    ->exists();
                if (!$enrolled) {
                    $missing[] = Lesson::find($lid);
                }
            }
            if (!empty($missing)) {
                $orphans[] = ['log' => $log, 'user' => $user, 'missing' => $missing];
            }
        }

        return view('admin.orphanPayments', compact('orphans'));
    }

    public function reconcileAllOrphans()
    {
        $paymentService = new PaymentService();
        $fixed = 0;

        $logs = PaymentLogs::where('created_at', '>=', now()->subYear())->orderBy('id')->get();
        foreach ($logs as $log) {
            [$lessonIds] = $paymentService->parseLessonAndSeminarIds($log);
            if (!count($lessonIds) || !$log->user_id) {
                continue;
            }
            foreach ($lessonIds as $lid) {
                $enrolled = LessonUser::where('user_id', $log->user_id)
                    ->where('lesson_id', $lid)
                    ->whereYear('created_at', date('Y'))
                    ->exists();
                if ($enrolled) {
                    continue;
                }
                $course = Lesson::find($lid);
                $result = AdminEnrollmentHelper::enrollUserInCourse((int) $log->user_id, (int) $lid, [
                    'price' => $course ? $course->price : $log->amount,
                ]);
                if (($result['type'] ?? '') === 'success') {
                    $fixed++;
                }
            }
        }

        return Redirect::route('admin_orphan_payments')->with('success', "Reconciled {$fixed} missing course enrollment(s).");
    }

    protected function courseCodeMap(): array
    {
        return [
            34 => 'CAT17',
            45 => 'CAT42',
            38 => 'CAT38',
            50 => 'CAT50',
            49 => 'CAT46',
            16 => 'CAT01',
        ];
    }

    protected function lessonLabel(Lesson $lesson): string
    {
        $code = $this->courseCodeMap()[$lesson->id] ?? 'Course';
        return "{$code} — {$lesson->title} (\${$lesson->price})";
    }
}
