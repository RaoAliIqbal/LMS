<?php

namespace App\Http\Controllers;

use App\Mail\CertificateMail;
use App\Models\Chapter;
use App\Models\CourseApproval;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\Seminar;
use App\Models\SeminarApproval;
use App\Notifications\NewSeminarAdmin;
use App\Notifications\NewSeminarUser;
use App\Services\PaymentService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\EducationService as Service;
use App\Services\EvaluationService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Services\QuizService;
use App\Http\Helpers\General\Helper;
use App\Models\Category;
use App\Models\State;
use App\Models\Approval;
use App\Models\DiscountCoupon;
use App\Models\UserEvaluation;
use App\Notifications\EvaluationNotification;
use PDF;
use Notification;
use App\Notifications\NewOrderAdmin;
use App\Notifications\NewOrderUser;
use App\UserDiscountLog;
use Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Support\StudentEnrollmentLoader;
use App\Support\AdminDbLoader;

class EducationController extends Controller
{

    public function lessonsLists(){
        $payload = $this->loadAdminLessonsFromDatabase();
        $lessons = $payload['lessons'];
        $dbDebug = $payload['dbDebug'];

        if ($payload['error']) {
            return view('admin.lesson.list', compact('lessons', 'dbDebug'))
                ->with('error', $payload['error']);
        }

        return view('admin.lesson.list', compact('lessons', 'dbDebug'));
    }

    /**
     * Load admin course list directly from DB (no service dependency).
     * Shows .env database name even when MySQL connection fails.
     */
    protected function loadAdminLessonsFromDatabase(): array
    {
        $dbDebug = [
            'env_database' => (string) env('DB_DATABASE', '(not set in .env)'),
            'env_host' => (string) env('DB_HOST', '(not set)'),
            'database' => (string) env('DB_DATABASE', '(not set in .env)'),
            'raw_count' => 0,
            'connection_ok' => false,
            'db_error' => null,
        ];

        try {
            DB::connection()->getPdo();
            $dbDebug['connection_ok'] = true;
            $dbDebug['database'] = (string) DB::connection()->getDatabaseName();
            $dbDebug['raw_count'] = (int) DB::table('lessons')->count();

            $lessons = collect(DB::table('lessons')->orderBy('id', 'asc')->get());

            if ($lessons->isNotEmpty()) {
                $evalIds = $lessons->pluck('evaluation_id')->filter()->unique()->values();
                $evalTitles = collect();
                if ($evalIds->isNotEmpty()) {
                    try {
                        $evalTitles = DB::table('evaluations')->whereIn('id', $evalIds)->pluck('title', 'id');
                    } catch (\Throwable $e) {
                        \Log::warning('admin lessons: evaluations lookup failed', ['message' => $e->getMessage()]);
                    }
                }
                foreach ($lessons as $lesson) {
                    $eid = $lesson->evaluation_id ?? null;
                    $lesson->evaluation = ($eid && isset($evalTitles[$eid]))
                        ? (object) ['id' => $eid, 'title' => $evalTitles[$eid]]
                        : null;
                }
            }

            if ($lessons->isEmpty() && $dbDebug['raw_count'] > 0) {
                return [
                    'lessons' => $lessons,
                    'dbDebug' => $dbDebug,
                    'error' => 'Database has '.$dbDebug['raw_count'].' courses but they could not be read. Check storage/logs/laravel.log.',
                ];
            }

            if ($lessons->isEmpty()) {
                return [
                    'lessons' => $lessons,
                    'dbDebug' => $dbDebug,
                    'error' => 'No courses in MySQL database "'.$dbDebug['database'].'" (0 rows in lessons table). '
                        .'Your local site uses database "'.$dbDebug['env_database'].'". '
                        .'In cPanel phpMyAdmin open the database where SELECT COUNT(*) FROM lessons shows 32, '
                        .'then set the same name in live .env as DB_DATABASE. '
                        .'Also delete bootstrap/cache/config.php and run php artisan config:clear.',
                ];
            }

            return ['lessons' => $lessons, 'dbDebug' => $dbDebug, 'error' => null];
        } catch (\Throwable $e) {
            \Log::error('admin lessons DB failed', [
                'message' => $e->getMessage(),
                'env_database' => $dbDebug['env_database'],
            ]);
            $dbDebug['db_error'] = $e->getMessage();

            return [
                'lessons' => collect(),
                'dbDebug' => $dbDebug,
                'error' => 'Cannot connect to MySQL. .env DB_DATABASE='.$dbDebug['env_database']
                    .', DB_HOST='.$dbDebug['env_host'].'. Error: '.$e->getMessage()
                    .'. Fix DB_HOST, DB_USERNAME, DB_PASSWORD in live .env (cPanel → MySQL Databases).',
            ];
        }
    }

    public function lessonsAdd(){
        $pageData = array('type'=>'add','name'=>'Add Course');
        $lesson = new Lesson();
        $evaluationService = new EvaluationService();
        $quizService = new QuizService();
        $evaluations = $evaluationService->list()['data'];
        $quiz = $quizService->quizList()['data'];
        $categories = Category::all();
        $states = State::all();
        $approvals = Approval::all();
        $courseCategory = [];
        $courseStates = [];
        $courseApprovals = [];
        return  view('admin.lesson.addLesson',compact('lesson','pageData','evaluations','quiz','categories','states','courseStates','courseCategory','courseApprovals','approvals'));
    }

    public function lessonsEdit($id){
        $pageData = array('type'=>'edit','name'=>'Edit Course');
        $service = new Service();
        $evaluationService = new EvaluationService();
        $evaluations = $evaluationService->list()['data'];
        $quizService = new QuizService();
        $quiz = $quizService->quizList()['data'];
        $cat = $service->getLesson($id);
        if ($cat['type'] == 'success'){ $lesson = $cat['data']; }else{ return abort('505'); }
        $categories = Category::all();
        $states = State::all();
        $approvals = Approval::all();
        $courseCategory = $lesson->categories()->pluck('category_id')->toArray();
        $courseStates = $lesson->states()->pluck('state_id')->toArray();
        $courseApprovals = $lesson->approvals()->pluck('approval_id')->toArray();
        return  view('admin.lesson.addLesson',compact('lesson','pageData','evaluations','quiz','categories','states','courseStates','courseCategory','approvals','courseApprovals'));
    }

    public function lessonsSave(Request $request){
        $service = new Service();
        $save = $service->LessonSave($request);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_lessons'))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_lessons'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function lessonsDelete($id){
        $service = new Service();
        $save = $service->LessonDelete($id);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_lessons'))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_lessons'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function viewChap($id){
        try {
            if (! Schema::hasTable('lessons') || ! Schema::hasTable('chapters')) {
                $data = ['lesson' => null, 'chapters' => collect()];

                return view('admin.lesson.chapters', compact('data'))
                    ->with('error', 'Database tables missing. '.AdminDbLoader::PATCH_HINT);
            }

            $lesson = Lesson::find($id);
            if (! $lesson) {
                $data = ['lesson' => null, 'chapters' => collect()];

                return view('admin.lesson.chapters', compact('data'))
                    ->with('error', 'Course not found.');
            }

            $chapters = Chapter::where('lesson_id', $id)
                ->with(['exercise', 'quiz'])
                ->orderBy('sort_by', 'asc')
                ->get();
            $data = ['lesson' => $lesson, 'chapters' => $chapters];

            return view('admin.lesson.chapters', compact('data'));
        } catch (\Throwable $e) {
            \Log::error('admin viewChap failed', ['lesson_id' => $id, 'message' => $e->getMessage()]);
            $data = ['lesson' => null, 'chapters' => collect()];

            return view('admin.lesson.chapters', compact('data'))
                ->with('error', 'Could not load chapters. '.$e->getMessage());
        }
    }

    public function ChapAdd($id){
        $pageData = array('type'=>'add','name'=>'Add Chapter');
        $service = new Service();
        $chapter = $service->iniChapter($id);
        $quizService = new QuizService();
        $quiz = $quizService->quizList()['data'];
        $lesson = $service->getLesson($id)['data'];
        $mainChapters = $service->getChapters($id,'main');
        $exercise = $quizService->exercisesList()['data'];
        $lessonId = $id;
        return redirect()->route('admin_edit_Chap',$chapter->id);

//        return  view('admin.lesson.ChapAdd',compact('lesson','chapter','mainChapters','pageData','exercise','lessonId','quiz'));
    }

    public function ChapEdit($id){
        $pageData = array('type'=>'chapter','name'=>'Chapter');
        $service = new Service();
        $quizService = new QuizService();
        $quiz = $quizService->quizList()['data'];
        $chapter = $service->getChapterById($id);
        $lessonId = $chapter->lesson_id;
        $mainChapters = $service->getChapters($chapter->lesson_id,'main');
        $exercise = $quizService->exercisesList()['data'];
        return  view('admin.lesson.ChapEdit',compact('chapter','pageData','mainChapters','exercise','lessonId','quiz'));
    }

    public function chapSave(Request $request){
        $quizService = new QuizService();
        $service = new Service();

        if($request->ajax()){
            $save = $service->chapTitleSave($request);
            return true;
        }
        $save = $service->chapSave($request);
        if ($save['type'] == 'error'){
            $chapter = $save['data'];
            $pageData = array('type'=>'add','name'=>'Add Chapter');
            $lesson = $service->getLesson($request->lesson_id)['data'];
            $mainChapters = $service->getChapters($request->lesson_id,'main');
            Session::flash('error', $save['msg']);
            $quiz = $quizService->quizList()['data'];
            $exercise = $quizService->exercisesList()['data'];
            return  view('admin.lesson.ChapAdd',compact('lesson','chapter','mainChapters','pageData','quiz','exercise'));
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_viewChap',$request->lesson_id))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function chapUpdate(Request $request){

        $service = new Service();
        $save = $service->chapUpdate($request);
        //  dd($save);
        if ($save['type'] == 'error'){
            $chapter = $save['data'];
            Session::flash('error', $save['msg']);
            $pageData = array('type'=>'chapter','name'=>'Edit Chapter');
            $service = new Service();
//            $chapter = $service->getChapterById($request->id);
            $mainChapters = $service->getChapters($request->lesson_id,'main');
            return  view('admin.lesson.ChapEdit',compact('chapter','pageData','mainChapters'));

        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_viewChap',$request->lesson_id))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function chapterDelete($id){
        $service = new Service();
        $delete = $service->chapterDelete($id);
        if ($delete['type'] == 'error'){
            return Redirect::to(route('admin_viewChap',$delete['lesson_id']))->with('error', $delete['msg']);
        }else if($delete['type'] == 'success'){
            return Redirect::to(route('admin_viewChap',$delete['lesson_id']))->with('info', $delete['msg']);
        }else{
            return abort('500');
        }
    }

    public function topicSave(Request $request){

        $service = new Service();
        $save = $service->topicSave($request);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_edit_Chap',$request->chapter_id))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_edit_Chap',$request->chapter_id))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }
    public function deleteTopic(Request $request){
        $service = new Service();
        $delete = $service->deleteTopic($request);
    }
    public function download(Request $request){
        if (file_exists($request->f)){
            return response()->download($request->f);
        }
        return redirect()->back()->with('error','File not found');
    }



    public function approvals(){
        $payload = $this->loadAdminApprovalsFromDatabase();
        $items = $payload['items'];

        if ($payload['error']) {
            return view('admin.approvals.list', compact('items'))
                ->with('error', $payload['error']);
        }

        return view('admin.approvals.list', compact('items'));
    }

    /**
     * Load approvals list directly from DB (avoids EducationService autoload failures on live).
     */
    protected function loadAdminApprovalsFromDatabase(): array
    {
        try {
            if (! Schema::hasTable('approvals')) {
                return [
                    'items' => collect(),
                    'error' => 'The approvals table is missing in MySQL. Import live-db/professionalonli_live_patch.sql in phpMyAdmin, then refresh this page.',
                ];
            }

            $items = collect(DB::table('approvals')->orderBy('id', 'asc')->get());

            return ['items' => $items, 'error' => null];
        } catch (\Throwable $e) {
            \Log::error('admin approvals DB failed', ['message' => $e->getMessage()]);

            return [
                'items' => collect(),
                'error' => 'Could not load approvals. Check storage/logs/laravel.log. Error: '.$e->getMessage(),
            ];
        }
    }
    public function approvalAdd(){
        $approval = new Approval();
        return view('admin.approvals.form', compact('approval'));
    }
    public function approvalEdit($id){
        $approval = Approval::find($id);
        if (!$approval){ return abort('404');exit(); }
        return view('admin.approvals.form', compact('approval'));
    }
    public function approvalSave(Request $request){

        $service = new Service();
        $save = $service->approvalSave($request);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_approvals'))->with('Error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_approvals'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }
    public function approvalDelete($id){
        $service = new Service();
        $delete = $service->deleteApprovals($id);
        if ($delete['type'] == 'error'){
            return Redirect::to(route('admin_approvals'))->with('Error', $delete['msg']);
        }else if($delete['type'] == 'success'){
            return Redirect::to(route('admin_approvals'))->with('info', $delete['msg']);
        }else{
            return abort('500');
        }
    }



    public function approvalCourses(){
        try {
            if (! Schema::hasTable('course_approvals')) {
                return view('admin.approvals.courseApprovals', ['approvals' => collect()])
                    ->with('error', 'Table `course_approvals` is missing. '.AdminDbLoader::PATCH_HINT);
            }

            $approvals = CourseApproval::with(['approval', 'course'])->orderBy('id', 'asc')->get();

            return view('admin.approvals.courseApprovals', compact('approvals'));
        } catch (\Throwable $e) {
            \Log::error('admin approvalCourses failed', ['message' => $e->getMessage()]);

            return view('admin.approvals.courseApprovals', ['approvals' => collect()])
                ->with('error', 'Could not load course approvals. '.$e->getMessage());
        }
    }
    public function approvalCourseAdd(){
        $service = new Service();

        $data = [
            'approvals' => $service->approvals(),
            'courses' => $service->LessonList()['data'],
            'categories'=>Category::all(),
            'states'=>State::all(),
            'courseApproval'=> new CourseApproval,
        ];
        return view('admin.approvals.courseApprovalForm')->with($data);
    }
    public function approvalCourseEdit($id){
        $service = new Service();

        $data = [
            'approvals' => $service->approvals(),
            'courses' => $service->LessonList()['data'],
            'categories'=>Category::all(),
            'states'=>State::all(),
            'courseApproval' => $service->courseApprovalById($id),
        ];
        return view('admin.approvals.courseApprovalForm')->with($data);
    }
    public function approvalCourseSave(Request $request){
        $service = new Service();
        $delete = $service->approvalCourseSave($request);
        if ($delete['type'] == 'error'){
            return Redirect::to(route('admin_courseApprovals'))->with('Error', $delete['msg']);
        }else if($delete['type'] == 'success'){
            return Redirect::to(route('admin_courseApprovals'))->with('info', $delete['msg']);
        }else{
            return abort('500');
        }
    }


    public function approvalSeminars(){
        try {
            if (! Schema::hasTable('seminar_approvals')) {
                return view('admin.approvals.SeminarApprovals', ['approvals' => collect()])
                    ->with('error', 'Table `seminar_approvals` is missing. '.AdminDbLoader::PATCH_HINT);
            }

            $approvals = SeminarApproval::with(['approval', 'seminar'])->orderBy('id', 'asc')->get();

            return view('admin.approvals.SeminarApprovals', compact('approvals'));
        } catch (\Throwable $e) {
            \Log::error('admin approvalSeminars failed', ['message' => $e->getMessage()]);

            return view('admin.approvals.SeminarApprovals', ['approvals' => collect()])
                ->with('error', 'Could not load seminar approvals. '.$e->getMessage());
        }
    }
    public function approvalSeminarAdd(){
        $service = new Service();
        $data = [
            'approvals' => $service->approvals(),
            'seminars' => Seminar::all(),
            'categories'=>Category::all(),
            'states'=>State::all(),
            'seminarApproval'=> new SeminarApproval(),
        ];
        return view('admin.approvals.seminarApprovalForm')->with($data);
    }
    public function approvalSeminarEdit($id){
        $service = new Service();
        $data = [
            'approvals' => $service->approvals(),
            'seminars' => Seminar::all(),
            'categories'=>Category::all(),
            'states'=>State::all(),
            'seminarApproval' => $service->seminarApprovalById($id),
        ];
        return view('admin.approvals.seminarApprovalForm')->with($data);
    }
    public function approvalSeminarSave(Request $request){
        $service = new Service();
        $delete = $service->approvalSeminarSave($request);
        if ($delete['type'] == 'error'){
            return Redirect::to(route('admin_SeminarApprovals'))->with('Error', $delete['msg']);
        }else if($delete['type'] == 'success'){
            return Redirect::to(route('admin_SeminarApprovals'))->with('info', $delete['msg']);
        }else{
            return abort('500');
        }
    }
    public function getTotalAmount($courses){
        $totalAmount = 0;
        foreach ($courses as $course){
            $totalAmount += (float) $course->price;
        }
        return $totalAmount;
    }
    public function getDiscount($code,$reqTotal){
        $discount = DiscountCoupon::findByCode($code);
        $total = $reqTotal;
        $newTotal = $total;
        if ($discount){
            if ($discount->type == 'flat'){
                $newTotal = max(0, $total - $discount->dicount);
            }else{
                $off = 100 - $discount->dicount ;
                $off = $off / 100;
                $newTotal = max(0, $off * $total);
            }
        }else{
            return false;
        }
        return ['total'=>$total, 'newTotal'=>$newTotal, 'discount_coupon_id'=>$discount->id];
    }

    /**
     * Enroll all lesson IDs from cart; returns ok=false when nothing was enrolled.
     */
    protected function enrollLessonCart(array $cart, Service $service): array
    {
        $cart = array_values(array_filter(array_map('intval', $cart)));
        $failures = [];
        $successes = 0;

        foreach ($cart as $lessonId) {
            if ($lessonId <= 0) {
                continue;
            }
            $courseModel = Lesson::find($lessonId);
            $result = $service->grantCourseToUser((int) auth()->id(), $lessonId, [
                'price' => $courseModel ? $courseModel->price : 0,
            ]);
            if (is_array($result) && ($result['type'] ?? '') === 'error') {
                $failures[] = [
                    'lesson_id' => $lessonId,
                    'message' => $result['msg'] ?? 'unknown',
                ];
                \Log::error('Enrollment failed after checkout', [
                    'user_id' => auth()->id(),
                    'course_id' => $lessonId,
                    'message' => $result['msg'] ?? 'unknown',
                ]);
            } else {
                $successes++;
            }
        }

        if ($successes === 0 && count($cart) > 0) {
            return [
                'ok' => false,
                'successes' => 0,
                'failures' => $failures,
                'message' => 'We could not add the course(s) to your profile. Please call 1-888-900-3382 — your payment may be on file.',
            ];
        }

        return [
            'ok' => true,
            'successes' => $successes,
            'failures' => $failures,
            'message' => count($failures)
                ? 'Some courses were added; our team was notified about the rest. Call 1-888-900-3382 if anything is missing.'
                : null,
        ];
    }

    protected function buildDiscountLogEntries(string $code, $courses): array
    {
        $entries = [];
        foreach ($courses as $course) {
            $getDiscount = $this->getDiscount($code, $course->price);
            if ($getDiscount === false) {
                continue;
            }
            $entries[] = [
                'user_id' => auth()->id(),
                'lesson_id' => $course->id,
                'discount_coupon_id' => $getDiscount['discount_coupon_id'],
                'total' => $getDiscount['total'],
                'new_total' => $getDiscount['newTotal'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        return $entries;
    }

    public function getPassedHoursFromRegistrationDate($created_at){//this function will return the hours that have been passed since user registered
        
        $created_at      = Date::parse(auth()->user()->created_at);
        $currentDateTime = now();
        $hoursPassed     = $currentDateTime->diffInHours($created_at);

        return $hoursPassed;
    }

    public function cartEnroll(Request  $request){
     
        $user = User::find(auth()->user()->id);
        Helper::prepareClientCartSessions();
        if (count(session('seminar_cart', []))) {
            return redirect()->route('checkoutPage')->with('info', 'Your cart includes live events. Complete checkout for all items together.');
        }
        $ageRestrictedIds = [38,50];
        if ($request->age){  $user->age = '18'; }
        $paymentService = new PaymentService();
        $cart = session()->get('cart');
        $cart = is_array($cart) ? $cart : [];
        if (! count($cart)) {
            return redirect()->route('addToCartPage')->with('error', 'Your cart is empty.');
        }
        $courses = Lesson::whereIn('id',$cart)->get();
        
        $total = $this->getTotalAmount($courses);
        $totalDiscount = 0;
        $user_discount_log_arr = [];

        if($request->code){
            if($request->code == 'RLC240' ){
                if($this->getPassedHoursFromRegistrationDate(auth()->user()->created_at) > 24){
                    return redirect()->back()->with('error','You could not avail the discount because it has been more than 24 hours since you registered');
                }
            }
          
            $getDiscount   = $this->getDiscount($request->code,$total);
            
            if($getDiscount != false){
                $total         = $getDiscount['newTotal'];
                $totalDiscount = $getDiscount['total']-$getDiscount['newTotal'];
                $user_discount_log_arr = $this->buildDiscountLogEntries($request->code, $courses);
            }else{
                return redirect()->back()->with('error','Invalid coupon code');

            }


        }

        $this->validateCheckoutRequest($request, (float) $total);

        $service = new Service();
        $enroll = ['ok' => true, 'message' => null];

        if ($total != 0) {
            $payment = $paymentService->handleonlinepay($request, $total, $cart, $request->code ?? 0);
        } else {
            $enroll = $this->enrollLessonCart($cart, $service);
            if (! $enroll['ok']) {
                return redirect()->back()->with('error', $enroll['message']);
            }
            $payment = $paymentService->handlewithoutpay($request, $total, $cart);
            $payment['transaction_id'] = 0;
        }
        if ($payment['msgType'] == 'success_msg') {
            if ($cart != null) {
                if ($total != 0) {
                    $enroll = $this->enrollLessonCart($cart, $service);
                    if (! $enroll['ok']) {
                        return redirect()->route('client_Courses')->with('error', $enroll['message']);
                    }
                }
                $coursesNames = Lesson::whereIn('id', $cart)->get();
                Helper::sendNotificationSafely(User::find(4), new NewOrderAdmin(['request'=>$request,'user'=>auth()->user(),'cart'=>$cart,'total'=>$total,'discount'=>$totalDiscount,'transaction_id'=>$payment['transaction_id'],'coursesNames'=>$coursesNames]), 'checkout admin');
                Helper::sendNotificationSafely($user, new NewOrderUser(['request'=>$request,'user'=>auth()->user(),'cart'=>$cart,'total'=>$total]), 'checkout user');
                
                if (count($user_discount_log_arr)) {
                    UserDiscountLog::insert($user_discount_log_arr);
                }

                Session::forget('cart');
                $flash = $enroll['message'] ?? null;

                return redirect()->route('client_Courses', ['success' => true])
                    ->with($flash ? 'warning' : 'success', $flash ?: 'Click on your course to begin coursework');
            }

            return redirect()->route('home');
        }
        return redirect()->back()->with('error',$payment['text']);
    }

    protected function subtotalForSeminars($seminars): float
    {
        $total = 0;
        foreach ($seminars as $seminar) {
            $total += (float) $seminar->price_for_display;
        }

        return $total;
    }

    /**
     * Full billing + (when total > 0) card fields — avoids Authorize.net AVS declines from empty address/zip.
     */
    protected function validateCheckoutRequest(Request $request, float $total): void
    {
        $rules = [
            'first_name' => 'required|string|max:120',
            'last_name' => 'required|string|max:120',
            'address' => 'required|string|max:1000',
            'city' => 'required|string|max:120',
            'state_id' => 'required|string|max:120',
            'zipcode' => 'required|string|max:30',
            'phone' => 'required|string|max:40',
            'email' => 'required|email|max:255',
        ];
        if ($total > 0) {
            $rules['owner'] = 'required|string|max:200';
            $rules['cardNumber'] = 'required|string|min:12|max:22';
            $rules['expiration-month'] = 'required|integer|between:1,12';
            $rules['expiration-year'] = 'required|integer|min:'.(int) date('Y').'|max:'.((int) date('Y') + 20);
            $rules['cvv'] = 'required|string|min:3|max:4';
        }
        $request->validate($rules, [
            'address.required' => 'Billing address is required.',
            'city.required' => 'City is required.',
            'zipcode.required' => 'ZIP or postal code is required (card payments often decline without it).',
        ]);
    }

    public function seminarCartEnroll(Request $request)
    {
        $user = User::find(auth()->user()->id);
        Helper::prepareClientCartSessions();
        $lessonCart = session()->get('cart');
        $lessonCart = is_array($lessonCart) ? $lessonCart : [];
        if (count($lessonCart)) {
            return redirect()->route('checkoutPage')->with('info', 'Your cart includes courses. Complete checkout for all items together.');
        }

        $seminarCart = session()->get('seminar_cart', []);
        if (! is_array($seminarCart)) {
            $seminarCart = [];
        }
        $seminarCart = array_values(array_unique(array_filter($seminarCart)));
        if (! count($seminarCart)) {
            return redirect()->route('addToCartPage')->with('error', 'Your cart is empty.');
        }

        $seminars = Seminar::whereIn('id', $seminarCart)->get();
        if ($seminars->count() !== count($seminarCart)) {
            return redirect()->route('addToCartPage')->with('error', 'One or more events are no longer available.');
        }

        $total = $this->subtotalForSeminars($seminars);
        $totalDiscount = 0;
        if ($request->code) {
            $getDiscount = $this->getDiscount($request->code, $total);
            if ($getDiscount == false) {
                return redirect()->back()->with('error', 'Invalid coupon code');
            }
            $total = $getDiscount['newTotal'];
            $totalDiscount = $getDiscount['total'] - $getDiscount['newTotal'];
        }

        $this->validateCheckoutRequest($request, (float) $total);

        $paymentService = new PaymentService();
        if ($total != 0) {
            $payment = $paymentService->handleonlinepay($request, $total, [], $request->code ?? 0, $seminarCart);
        } else {
            $payment = $paymentService->handlewithoutpay($request, $total, $seminarCart);
            $payment['transaction_id'] = 0;
        }
        if ($payment['msgType'] == 'success_msg') {
            $service = new Service();
            foreach ($seminarCart as $sid) {
                $service->seminarEnroll($sid);
            }
            foreach ($seminars as $seminar) {
                Helper::sendNotificationSafely(User::find(4), new NewSeminarAdmin(['request' => $request, 'user' => auth()->user(), 'total' => $total, 'discount' => $totalDiscount, 'transaction_id' => $payment['transaction_id'] ?? 0, 'seminar' => $seminar]), 'seminar checkout admin');
                Helper::sendNotificationSafely($user, new NewSeminarUser(['request' => $request, 'user' => auth()->user(), 'seminar' => $seminar, 'total' => $total]), 'seminar checkout user');
            }
            Session::forget('seminar_cart');

            return redirect()->route('client_events')->with('info', 'Thank you for your order an email will be sent to you with instructions.');
        }

        return redirect()->back()->with('error', $payment['text']);
    }

    public function unifiedCartEnroll(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $ageRestrictedIds = [38, 50];
        if ($request->age) {
            $user->age = '18';
        }
        Helper::prepareClientCartSessions();
        $cart = session()->get('cart');
        $cart = is_array($cart) ? $cart : [];
        $seminarCart = session()->get('seminar_cart', []);
        if (! is_array($seminarCart)) {
            $seminarCart = [];
        }
        $seminarCart = array_values(array_unique(array_filter($seminarCart)));

        if (! count($cart) || ! count($seminarCart)) {
            return redirect()->route('checkoutPage')->with('error', 'Invalid checkout session. Please try again.');
        }

        $courses = Lesson::whereIn('id', $cart)->get();
        if ($courses->count() !== count($cart)) {
            return redirect()->route('checkoutPage')->with('error', 'One or more courses are no longer available.');
        }
        $seminars = Seminar::whereIn('id', $seminarCart)->get();
        if ($seminars->count() !== count($seminarCart)) {
            return redirect()->route('checkoutPage')->with('error', 'One or more events are no longer available.');
        }

        $total = $this->getTotalAmount($courses) + $this->subtotalForSeminars($seminars);
        $totalDiscount = 0;

        if ($request->code) {
            if ($request->code == 'RLC240') {
                if ($this->getPassedHoursFromRegistrationDate(auth()->user()->created_at) > 24) {
                    return redirect()->back()->with('error', 'You could not avail the discount because it has been more than 24 hours since you registered');
                }
            }
            $getDiscount = $this->getDiscount($request->code, $total);
            if ($getDiscount == false) {
                return redirect()->back()->with('error', 'Invalid coupon code');
            }
            $total = $getDiscount['newTotal'];
            $totalDiscount = $getDiscount['total'] - $getDiscount['newTotal'];
        }

        $this->validateCheckoutRequest($request, (float) $total);

        $paymentService = new PaymentService();
        $service = new Service();
        $enroll = ['ok' => true, 'message' => null];

        if ($total != 0) {
            $payment = $paymentService->handleonlinepay($request, $total, $cart, $request->code ?? 0, $seminarCart);
        } else {
            $enroll = $this->enrollLessonCart($cart, $service);
            if (! $enroll['ok']) {
                return redirect()->back()->with('error', $enroll['message']);
            }
            $payment = $paymentService->handlewithoutpay($request, $total, $cart);
            $payment['transaction_id'] = 0;
        }
        if ($payment['msgType'] == 'success_msg') {
            if ($total != 0) {
                $enroll = $this->enrollLessonCart($cart, $service);
                if (! $enroll['ok']) {
                    return redirect()->route('client_Courses')->with('error', $enroll['message']);
                }
            }
            foreach ($seminarCart as $sid) {
                $service->seminarEnroll($sid);
            }
            $coursesNames = Lesson::whereIn('id', $cart)->get();
            Helper::sendNotificationSafely(User::find(4), new NewOrderAdmin(['request' => $request, 'user' => auth()->user(), 'cart' => $cart, 'total' => $total, 'discount' => $totalDiscount, 'transaction_id' => $payment['transaction_id'], 'coursesNames' => $coursesNames]), 'unified checkout admin');
            Helper::sendNotificationSafely($user, new NewOrderUser(['request' => $request, 'user' => auth()->user(), 'cart' => $cart, 'total' => $total]), 'unified checkout user');
            foreach ($seminars as $seminar) {
                Helper::sendNotificationSafely(User::find(4), new NewSeminarAdmin(['request' => $request, 'user' => auth()->user(), 'total' => $total, 'discount' => $totalDiscount, 'transaction_id' => $payment['transaction_id'] ?? 0, 'seminar' => $seminar]), 'unified seminar admin');
                Helper::sendNotificationSafely($user, new NewSeminarUser(['request' => $request, 'user' => auth()->user(), 'seminar' => $seminar, 'total' => $total]), 'unified seminar user');
            }
            Session::forget('cart');
            Session::forget('seminar_cart');

            $flash = $enroll['message'] ?? null;

            return redirect()->route('client_Courses', ['success' => true])
                ->with($flash ? 'warning' : 'success', $flash ?: 'Your order is complete. Courses appear in My Courses; live events under Live Events.');
        }

        return redirect()->back()->with('error', $payment['text']);
    }

    public function devCartEmail(Request $request){
        $cart = session()->get('cart');
        $total = 500;
        $coursesNames = Lesson::whereIn('id',$cart)->get();

        Notification::send(User::find(4), new NewOrderAdmin(['request'=>$request,'user'=>auth()->user(),'cart'=>$cart,'total'=>$total,'coursesNames'=>$coursesNames,'discount'=>$total-250,'transaction_id'=>'123564']));
    }

    public function devCartEnroll(Request  $request){
        $user = User::find(auth()->user()->id);
        $userCourses = LessonUser::where('user_id',$user->id)->pluck('lesson_id')->toArray();
        $cart = session()->get('cart');
        $courses = Lesson::whereNotIn('id',$userCourses)->get();
        $service = new Service();
        foreach ($courses as $course){
            $service->courseEnroll($course->id);
        }
        echo 'done';
        return '';
    }

    // Client
    public function seminarEnroll(Request $request,$id){
        $service = new Service();
        $seminar = Seminar::find($id);
        if (! $seminar) {
            return redirect()->route('client_events')->with('error', 'This seminar is no longer available.');
        }
        $total = (float) $seminar->price_for_display;
            $totalDiscount = 0;
        if ($request->code){
            $getDiscount = $this->getDiscount($request->code,$total);
//            dd($getDiscount);
            $total = $getDiscount['newTotal'];
            $totalDiscount = $getDiscount['total']-$getDiscount['newTotal'];
        }
        $paymentService = new PaymentService();
//        dd($total);
        if ($total != 0) {
            $payment = $paymentService->handleseminaronlinepay($seminar,$total,$request,$request->code);
        } else {
            $payment = $paymentService->handlewithoutpay($request, $total, [$id]);
            $payment['transaction_id'] = 0;
        }

        if ($payment['msgType'] == 'success_msg') {
        $service->seminarEnroll($id);
        Helper::sendNotificationSafely(User::find(4), new NewSeminarAdmin(['request'=>$request,'user'=>auth()->user(),'total'=>$total,'discount'=>$totalDiscount,'transaction_id'=>$payment['transaction_id'],'seminar'=>$seminar]), 'seminar enroll admin');
        Helper::sendNotificationSafely(auth()->user(), new NewSeminarUser(['request'=>$request,'user'=>auth()->user(),'seminar'=>$seminar,'total'=>$total]), 'seminar enroll user');
        return redirect()->route('client_events')->with('info','Thank you for your order an email will be sent to you with instructions.');
        }
        return redirect()->back()->with('error',$payment['text']);
    }

    public function sendNewSeminar($userId, $seminarId){
        $seminar = Seminar::find($seminarId);
        $user = User::find($userId);
        Helper::sendNotificationSafely($user, new NewSeminarUser(['request'=>[],'user'=>$user,'seminar'=>$seminar,'total'=>'']), 'resend seminar user');
        return 'sent';
    }

    public function courseEnroll($id){
        $service = new Service();
        $result = $service->courseEnroll($id);
        if (is_array($result) && ($result['type'] ?? '') === 'error') {
            return redirect()->route('client_Courses')->with('error', $result['msg']);
        }
        return redirect()->route('client_Courses')->with('success', 'You have been enrolled successfully.');
    }
    public function myCourses(){
        if (auth()->user() && auth()->user()->hasRole('Admin')) {
            return redirect()->route('admin_dashboard');
        }

        $user = auth()->user();
        $courses = collect();

        try {
            $courses = StudentEnrollmentLoader::ensureEnrollments($user);
        } catch (\Throwable $e) {
            \Log::error('myCourses loader failed', [
                'user_id' => optional($user)->id,
                'message' => $e->getMessage(),
            ]);
        }

        if ($courses->isEmpty()) {
            $courses = $this->loadMyCoursesDirect((int) $user->id, (string) $user->email);
        }

        return view('client.courses', ['courses' => $courses]);
    }

    /**
     * Direct DB load for My Courses (fallback when service/class fails on live).
     */
    public function loadMyCoursesDirect(int $userId, string $email = ''): \Illuminate\Support\Collection
    {
        try {
            if (! Schema::hasTable('lesson_users')) {
                return collect();
            }

            $userIds = array_values(array_unique(array_filter([$userId])));
            if ($email !== '' && Schema::hasTable('users')) {
                $resolvedId = (int) DB::table('users')->where('email', $email)->value('id');
                if ($resolvedId > 0) {
                    $userIds[] = $resolvedId;
                    $userIds = array_values(array_unique($userIds));
                }
            }

            $query = DB::table('lesson_users as lu')->whereIn('lu.user_id', $userIds);

            if (Schema::hasTable('lessons')) {
                $query->leftJoin('lessons as l', 'l.id', '=', 'lu.lesson_id');
            }

            $select = ['lu.id', 'lu.lesson_id', 'lu.user_id', 'lu.enrolled_at', 'lu.completed_at', 'lu.price', 'lu.status', 'lu.is_show', 'lu.created_at', 'lu.updated_at'];
            if (Schema::hasTable('lessons')) {
                $select[] = 'l.title';
                $select[] = 'l.hours';
            }

            return $query
                ->select($select)
                ->orderByDesc('lu.id')
                ->get()
                ->unique('lesson_id')
                ->map(function ($row) {
                    $lesson = (object) [
                        'id' => (int) $row->lesson_id,
                        'title' => ($row->title ?? null) ?: ('Course #'.$row->lesson_id),
                        'hours' => $row->hours ?? null,
                        'categories' => collect(),
                    ];

                    return (object) [
                        'id' => $row->id,
                        'lesson_id' => $row->lesson_id,
                        'user_id' => $row->user_id,
                        'enrolled_at' => $row->enrolled_at ?? $row->created_at,
                        'completed_at' => $row->completed_at,
                        'price' => $row->price,
                        'status' => $row->status ?? 1,
                        'is_show' => $row->is_show ?? 0,
                        'created_at' => $row->created_at ?? $row->enrolled_at ?? now(),
                        'updated_at' => $row->updated_at,
                        'course' => $lesson,
                        'courseWithTrashed' => $lesson,
                    ];
                })
                ->values();
        } catch (\Throwable $e) {
            \Log::error('loadMyCoursesDirect failed', [
                'user_id' => $userId,
                'message' => $e->getMessage(),
            ]);

            return collect();
        }
    }
    public function myCourse($id, $year=null){
        try {
            $service = new Service();
            $year = $service->resolveEnrollmentYear(auth()->id(), $id, $year);
            $data = $service->MyCourse($id, $year);
            $data['year'] = $year;

            if ($data['type'] == 'error'){
                return Redirect::to(route('client_Courses'))->with('error', $data['msg']);
            }elseif($data['type'] == 'success'){
                return view('client.courseDetails')->with($data);
            }
            return redirect()->route('client_Courses')->with('error', 'Course could not be loaded. Please try again.');
        } catch (\Throwable $e) {
            \Log::error('myCourse failed: '.$e->getMessage(), [
                'user_id' => auth()->id(),
                'course_id' => $id,
                'year' => $year ?? null,
            ]);
            return redirect()->route('client_Courses')
                ->with('error', 'Course could not be opened. Please try again or call 1-888-900-3382.');
        }
    }
    public function addTime(Request $request){
        try {
            $service = new Service();
            $service->addTime($request);
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            \Log::error('addTime failed: ' . $e->getMessage(), ['user_id' => auth()->id()]);
            return response()->json(['success' => false, 'message' => 'Failed to save time'], 200);
        }
    }
    public function saveUserExercise(Request $request){
        $service = new Service();
        $data = $service->saveUserExercise($request);
//        dd($data['exercise']->questions);
        return view('client.partials.correctAns')->with($data);
    }
    public function myCourseChapter($id,$chapterId,$year){

        $service = new Service();
        $user = auth()->user();
        $data = $service->myCourseChapter($id,$chapterId,$user,$year);
        $data['year'] = $year;
        if ($data['type'] == 'error'){
            return Redirect::to(route('client_Courses'))->with('Error', $data['msg']);
        }else if($data['type'] == 'success'){
            return view('client.chapterDetails')->with($data);
        }else{
            return redirect()->route('client_Courses')->with('error', 'Chapter could not be loaded. Please try again.');
        }

    }
    public function topicsDev($id,$chapterId){
 
        $service = new Service();
        $user = new \stdClass();
        $user->id  = 43;
        $data = $service->myCourseChapter($id,$chapterId,$user);
        $data['quizzes'] = $service->userQuizzes($user, null);
        $data['user'] = $user;
        if ($data['type'] == 'error'){
            return Redirect::to(route('client_Courses'))->with('Error', $data['msg']);
        }else if($data['type'] == 'success'){
            return view('client.course')->with($data);
        }else{
            return redirect()->route('client_Courses')->with('error', 'Course could not be loaded. Please try again.');
        }

    }
    public function topics($id,$chapterId, $year){
        try {
            $service = new Service();
            $user = auth()->user();
            $data = $service->myCourseChapter($id,$chapterId,$user,$year);
            $data['quizzes'] = $service->userQuizzes($user, $year, 'array');
            $data['user'] = $user;
            $data['year'] = $year;
            if ($data['type'] == 'error'){
                return Redirect::to(route('client_Courses'))->with('error', $data['msg']);
            }elseif($data['type'] == 'success'){
                return view('client.course')->with($data);
            }
            return redirect()->route('client_Courses')->with('error', 'Lesson could not be loaded. Please try again.');
        } catch (\Throwable $e) {
            \Log::error('topics failed: '.$e->getMessage(), ['user_id' => auth()->id(), 'course_id' => $id]);
            return redirect()->route('client_Course', ['id' => $id, 'year' => $year])
                ->with('error', 'Chapter could not be opened. Please try again or call 1-888-900-3382.');
        }
    }
    public function chapterComplete($id,$chapterId,$year,$per=false){
        
        $service = new Service();
        $chapter = $service->getChapterById($chapterId);
        $data = $service->chapterComplete($id,$chapterId);
        $quiz = $service->getQuiz($id,$chapterId);

        if ($data['type'] == 'error'){
            return redirect()
                   ->route('client_Course', ['id' => $id, 'year' => $year])->with('error','Something went wrong, Please try again.');
        }else if($data['type'] == 'success'){
            $msg = 'Congratulations you have successfully completed <b>'.$chapter->title.'</b>.';
            if ($quiz != null){ $msg .= $quiz->user_percentage.'%. <br /> Please go on your dashboard in order to generate your certificate'; }
            return redirect()->route('client_Course', ['id' => $id, 'year' => $year])->with('success',$msg);
        }else{
            return redirect()->route('client_Course', ['id' => $id, 'year' => $year])->with('error', 'Something went wrong. Please try again.');
        }

    }
    
        public function courseEvaluation($id, $year=null){
        $service = new Service();
        $year = $service->resolveEnrollmentYear(auth()->id(), $id, $year);
        $data = $service->getcourseEvaluation($id, $year);
        $data['year'] = $year;

        if ($data['type'] == 'error'){
            return redirect()
                ->route('client_Course', ['id' => $id, 'year' => $year])->with('error','Something went wrong, Please try again.');
        }
        if ($data['type'] == 'notFound'){
            return redirect()
                ->route('client_Course', ['id' => $id, 'year' => $year])->with('error','go to certificate');
        }
        if ($data['type'] == 'reprinting'){
            return redirect()->route('generateCertificate',[$id, 'false', $year]);
        }
        if ($data['type'] != 'success'){
            return redirect()->route('client_Courses')->with('error', 'Something went wrong. Please try again or call 1-888-900-3382.');
        }

        $get_orignal_course = auth()->user()->courses()
            ->where('lessons.id', $data['course']->id)
            ->first();
        $title = $data['evaluation']->title ?? '';
        $hasCE = str_contains($title, 'CE');
        $hasCA = str_contains($title, 'Initial CA');

        if ($hasCE xor $hasCA) {
            if ($get_orignal_course && $get_orignal_course->price == $data['course']->price) {
                $data['eligible_for_discount'] = true;
            }
            if ($hasCE) {
                $data['course_type'] = 'CE';
            }
            if ($hasCA) {
                $data['course_type'] = 'CA';
            }
        }

        return view('client.evaluation')->with($data);
    }
    
    public function sendCourseCompletion($user,$course,$year=null){
        $service = new Service();
        $data = $service->sendCourseCompletion($user,$course, $year);
        return 'sent';
    }
    public function saveEvaluation(Request $request){
        try {
            $service = new Service();
            $service->saveEvaluation($request);
            $year = $service->resolveEnrollmentYear(auth()->id(), $request->course_id, $request->year ?? null);
            return redirect()
                ->route('generateCertificate',[$request->course_id, 'true', $year]);
        } catch (\Throwable $e) {
            \Log::error('saveEvaluation failed: '.$e->getMessage(), ['user_id' => auth()->id()]);
            $year = (new Service())->resolveEnrollmentYear(auth()->id(), $request->course_id, $request->year ?? null);
            return redirect()
                ->route('client_Course', ['id' => $request->course_id, 'year' => $year])
                ->with('error', 'Your evaluation was saved but certificate generation failed. Please contact support at 1-888-900-3382.');
        }
    }
    public function sendEvalDev($userId,$courseId){
        $service = new Service();
        return $service->sendEvalDev($userId,$courseId);
    }

    public function UploadUserFiles(Request $request){
        $service = new Service();
        $userID = auth()->user()->id;
        $data = $service->UploadUserFiles($request,$userID);
        return redirect()->back()->with('info','File Uploaded');
    }
    public function generateCertificate($id, $send_mail=true, $year=null){

        $service = new Service();
        $user = auth()->user();
        $year = $service->resolveEnrollmentYear($user->id, $id, $year);

        // Guard: course must be completed before a certificate is generated
        $isAllowed = $service->isCertificateAllowed($user->id, $id, $year);
        if (!$isAllowed) {
            return redirect()->route('client_Course', ['id' => $id, 'year' => $year])
                ->with('error', 'You must complete all course requirements before generating a certificate.');
        }

        $userCourse = $service->getUserCourse($id, $year);

        // Guard: completed_at must be set (markCourseCompleted stamps it on evaluation submit)
        if (!$userCourse || !$userCourse->completed_at) {
            return redirect()->route('client_Course', ['id' => $id, 'year' => $year])
                ->with('error', 'Course completion record not found. Please contact support.');
        }

        $certificate_year = Carbon::parse($userCourse->completed_at)->format('y');

        $userName = $user->FullName;
        $course = Lesson::find($id);
//        TODO: validate multi categories.
        if (empty($course->categories) || !in_array($course->categories[0]->category_id, config('settings.CeCategories'))){
            return redirect()->route('client_Course', ['id' => $id, 'year' => $year])
                ->with('info', 'Congratulations! Your certificate will be sent by mail. You should receive it in the next 14 days.');
        }

        try {
        $courseId = substr($course->title,'0','5');
        // dd($courseId);
        $student_state = State::where('name',$user->state)->first();

        if (!$student_state){
            $student_state_id = 1; // default to AL
            $student_state = 'AL';
        }else{
            $student_state_id = $student_state->id;
            $student_state = $student_state->slug;
        }



//        PDF::SetTitle('Hello World');
//        PDF::AddPage();
//        PDF::Write(0, 'Hello World');
//        PDF::Output('hello_world.pdf');
// create new PDF document
//      $pdf = new PDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//dd($pdf);dd
// set document information

        PDF::SetCreator('PDF_CREATOR');
        PDF::SetAuthor('Frank Wallace, (916) 208-6310');
        PDF::SetTitle('Professional Online Education Certificate');

// remove default header/footer
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);

// set default monospaced font
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//PDF::SetMargins(0, 0, 0, true);

// set auto page breaks
        PDF::SetAutoPageBreak(FALSE, 0);

// set image scale factor
//PDF::setImageScale(PDF_IMAGE_SCALE_RATIO);
        PDF::setImageScale(1);
        // add a page
        PDF::AddPage();

// set cell padding
        PDF::setCellPaddings(1, 1, 1, 1);

// set cell margins
        PDF::setCellMargins(1, 1, 1, 1);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

        PDF::Image(url('new_border.png'),  1, 0, 279, 210, 'png', '', '', false, 300, '', false, false, 0, false, false, false);

        PDF::SetFont('times', 'B', 30);
        $txt = 'CERTIFICATE OF COMPLETION';
        PDF::MultiCell(242, 10, ''.$txt, 0, 'C', 0, 0, '', '', true, 5, false, true, 40, 'T');

        PDF::Ln(18);

        PDF::SetFont('times', '', 15);
        $txt = 'This is to certify that';
        PDF::MultiCell(242, 10, ' '.$txt, 0, 'C', 0, 0, '', '', true);

        PDF::Ln(10);

        PDF::SetFont('timesI', 'U', 25);
        PDF::MultiCell(242, 10, ' '.$userName, 0, 'C', 0, 0, '', '', true);

        PDF::Ln(14);

        PDF::SetFont('times', '', 15);

        $txt = 'has successfully completed a ' . $course->hours . ' hour course of study in';

        if($course->id == '42'){
            $txt = 'has successfully completed a ' . $course->hours . ' CE hour of online home study in';

        }
        PDF::MultiCell(242, 10, ' '.$txt, 0, 'C', 0, 0, '', '', true);
        
        if(strlen($course->title) < 66)
        {

            PDF::Ln(13);

            PDF::SetFont('timesI', '', 25);
            PDF::MultiCell(242, 10, ' ' . $course->title, 0, 'C', 0, 0, '', '', true);

            PDF::Ln(18);
        }
        else
        {

            $break_point = strpos($course->title, " ", 55);
            $course_title = wordwrap($course->title, $break_point, "\n");

            PDF::Ln(10);

            PDF::SetFont('timesI', '', 20);
            PDF::MultiCell(242, 10, ' ' . $course->title, 0, 'C', 0, 0, '', '', true);

            PDF::Ln(22);
        }

        PDF::SetFont('times', '', 15);
        $txt = 'with Canham College, a PACE approved provider, at www.Professional Online Education.com';
        PDF::MultiCell(242, 10, ' '.$txt, 0, 'C', 0, 0, '', '', true);

        PDF::Ln(10);

        $board_text = '';
 
        if($student_state == 'OR')
        {
            $board_text = 'in compliance with the Oregon Board of Chiropractic Examiners';
        }
        elseif($student_state == 'NV')
        {
            $board_text = 'in compliance with the Chiropractic Physicians\' Board of Nevada';
        }
        elseif($student_state == 'MD')
        {
            $board_text = 'in compliance with the Maryland Board of Chiropractic Examiners';
        }
        elseif($student_state == 'SD')
        {
            $board_text = 'in compliance with the South Dakota Board of Chiropractic Examiners';
        }
        elseif($student_state == 'CA')
        {
            $board_text = 'in compliance with the California Board of Chiropractic Examiners';
        }
        elseif($student_state == 'OH')
        {
            $board_text = 'in compliance with the Ohio State Chiropractic Board';
        }

        
        $test_date = Carbon::parse($userCourse->completed_at)->format(' \t\h\i\s \<\i\>\<\u\>jS\<\/\u\>\<\/\i\> \d\a\y \o\f \<\i\>\<\u\>F, Y\<\/\u\>\<\/\i\>\.');

        $txt = $board_text . $test_date;
   
        PDF::SetFont('times', '', 15);
        PDF::MultiCell(242, 10, ''.$txt, 0, 'C', 0, 0, '', '', true, 20, true, true, 40, 'T');

        PDF::Ln(10);
        // if($course->id == '42'){
        //     PDF::Image(url('pace_logo.png'), 50, 130, 37, 52, 'png', '', '', false, 520, '', false, false, 0, false, false, false);
        // }elseif($course->id != '47'){
        //     PDF::Image(url('pace_logo_single.png'), 50, 140, 37, 40, 'png', '', '', false, 520, '', false, false, 0, false, false, false);
        // }
        PDF::Image(url('pace_seal.png'), 50, 140, 37, 40, 'png', '', '', false, 520, '', false, false, 0, false, false, false);


        // if($course->id == '42'){
        //     PDF::Image(url('pace_logo.png'), 50, 130, 37, 52, 'png', '', '', false, 520, '', false, false, 0, false, false, false);
        // }elseif($course->id != '47'){
        //     PDF::Image(url('pace_logo_single.png'), 50, 140, 37, 40, 'png', '', '', false, 520, '', false, false, 0, false, false, false);
        // }


        $cert_no = $userCourse->id;
            
// FORMAT THE CERT_NO TO HAVE 4 DIGITS
        $cert_no = sprintf('%04d', $cert_no);
    
// COMPILE THE CERT NUMBER
           
        // $certificate_number =$student_state . $certificate_year  . $courseId . $cert_no;
        $certificate_number =$student_state . $certificate_year  . $course->title_dev . $cert_no;
           
        $approvals = 'CE# ' . $certificate_number;

//GET PACE APPROVAL NUMBER IF APPLICABLE
        $pace_approval = $service->approvalNo($course, '1', $userCourse->completed_at, $student_state_id);
       
//$pace_approval ='';
        if($pace_approval != null)
            $approvals .= "<br>PACE# " . $pace_approval;

        
// GET STATE APPROVAL NUMBER IF APPLICABLE
        $state_approval = $service->approvalNo($course, '2', $userCourse->completed_at, $student_state_id);
            
        if($state_approval != null)
            $approvals .= '<br>' . $student_state . ' APPROVAL# ' . $state_approval;

        // if($course->id == '42'){
        //     $approvals .= '<br>NCMTMB# 0001';
        // }
       
        $approvals .= '<br>mgr@professionalonlineeducation.com<br>1-888-900-3382';
   
        PDF::SetFont('times', '', 11);

        PDF::MultiCell(80, 100, ''.$approvals, 1, 'L', 0, 0, 100, 145, true, 200, true, true, 40, 'T');


        PDF::Image(url('ros_sig.jpg'), 170, 140, 79, 22, 'jpg', '', '', false, 300, '', false, false, 0, false, false, false);


        PDF::SetFont('times', '', 12);
        $txt = 'Rosalind Canham DC<br><i>Instructor</i><br/>2934 E. Avalon Drive<br/>Phoenix, AZ 85016 ';
        PDF::MultiCell(100, 10, ''.$txt, 1, 'L', 0, 0,175, 160, true, 20, true, true, 40, 'T');

        // Mailing address with suite/apartment for certificate verification and shipping
        $mailingAddress = $user->address;
        if (!empty($user->suite)) {
            $mailingAddress .= ', ' . $user->suite;
        }
        $mailingAddress .= ', ' . $user->city . ', ' . $user->state . ' ' . $user->zipcode;
        PDF::SetFont('times', '', 9);
        PDF::MultiCell(242, 5, 'Mailing: ' . $mailingAddress, 0, 'C', 0, 0, 15, 198, true, 5, false, true, 15, 'T');
// ---------------------------------------------------------

        if($send_mail == 'true'){//this will send the evaluation message and certificate copy to the admin email when its true or when user has already filled out the evalution form
            $userEvaluation = UserEvaluation::where(['user_id'=>auth()->id(), 'evaluation_id'=>$course->evaluation_id, 'lesson_id'=>$course->id])->first();
            Helper::sendNotificationSafely(User::find(4), new EvaluationNotification(['request'=>$userEvaluation,'user'=>auth()->user(),'lesson'=>$course,'userLesson'=>$userCourse]), 'certificate evaluation admin');
    
            $pdfContent = PDF::Output('', 'S'); // Get the PDF content as a string
            $file = str_replace(' ', '_', "uploads/certificates/".$userName.rand(111111,999999).".pdf"    ); // Define the path to the public directory and the file name
            file_put_contents($file, $pdfContent);
    
            try {
                Mail::raw("This is a certtificate attatchment of $userName", function ($message) use ($file) {
                    $message->to(config('settings.admin_email'))
                        ->subject('Certifical Attachment')
                        ->from('no-reply@professionalonlineeducation.com', 'no-reply')
                        ->attach(asset($file), [
                            'as' => 'attachment.pdf',
                        ]);
                });
            } catch (\Throwable $mailError) {
                \Log::error('Certificate mail failed: ' . $mailError->getMessage(), ['user_id' => $user->id, 'course_id' => $id]);
            }
        }

    //Close and output PDF document
        if (ob_get_contents()) {
            ob_end_clean(); // Clear the buffer
        }
        PDF::Output('certificate.pdf', 'I');
      

        return redirect()->route('client_Courses');

        } catch (\Throwable $e) {
            \Log::error('generateCertificate PDF failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'course_id' => $id,
                'year' => $year,
            ]);
            return redirect()->route('client_Course', ['id' => $id, 'year' => $year])
                ->with('error', 'We could not generate your certificate right now. Please contact support at 1-888-900-3382.');
        }


    }
    public function myEvents(){
        try {
            $service = new Service();
            $data = $service->MyEvents();
            if (! isset($data['seminars'])) {
                $data['seminars'] = collect();
            }
            return view('client.seminars')->with($data);
        } catch (\Throwable $e) {
            \Log::error('myEvents failed: '.$e->getMessage(), ['user_id' => auth()->id()]);
            return view('client.seminars', ['seminars' => collect()]);
        }
    }

    public function generateSeminarCertificate($id)
    {
        $user   = auth()->user();
        $seminar = \App\Models\Seminar::find($id);

        if (!$seminar) {
            return redirect()->route('client_events')->with('error', 'Seminar not found.');
        }

        // Find the pivot record for this user + seminar
        $seminarUser = \App\Models\SeminarUser::where('seminar_id', $id)
                                               ->where('user_id', $user->id)
                                               ->first();

        if (!$seminarUser || !$seminarUser->completed_at) {
            return redirect()->route('client_events')
                             ->with('error', 'Certificate is not yet available. Please contact support.');
        }

        $userName       = $user->FullName;
        $completedAt    = \Carbon\Carbon::parse($seminarUser->completed_at);
        $hours          = $seminar->ceus ?: $seminar->hours ?: '0';

        // ── Build PDF ───────────────────────────────────────────────────
        PDF::SetCreator('PDF_CREATOR');
        PDF::SetAuthor('Canham College, Inc.');
        PDF::SetTitle('Seminar Certificate of Completion');
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::setImageScale(1);
        PDF::AddPage();
        PDF::setCellPaddings(1, 1, 1, 1);
        PDF::setCellMargins(1, 1, 1, 1);

        PDF::Image(url('new_border.png'), 1, 0, 279, 210, 'png', '', '', false, 300, '', false, false, 0, false, false, false);

        PDF::SetFont('times', 'B', 30);
        PDF::MultiCell(242, 10, 'CERTIFICATE OF COMPLETION', 0, 'C', 0, 0, '', '', true, 5, false, true, 40, 'T');

        PDF::Ln(18);

        PDF::SetFont('times', '', 15);
        PDF::MultiCell(242, 10, ' This is to certify that', 0, 'C', 0, 0, '', '', true);

        PDF::Ln(10);

        PDF::SetFont('timesI', 'U', 25);
        PDF::MultiCell(242, 10, ' ' . $userName, 0, 'C', 0, 0, '', '', true);

        PDF::Ln(14);

        PDF::SetFont('times', '', 15);
        PDF::MultiCell(242, 10, ' has successfully attended a ' . $hours . ' hour live seminar in', 0, 'C', 0, 0, '', '', true);

        PDF::Ln(13);

        PDF::SetFont('timesI', '', 22);
        PDF::MultiCell(242, 10, ' ' . $seminar->course_title, 0, 'C', 0, 0, '', '', true);

        PDF::Ln(18);

        PDF::SetFont('times', '', 15);
        PDF::MultiCell(242, 10, ' with Canham College, a PACE approved provider, at www.ProfessionalOnlineEducation.com', 0, 'C', 0, 0, '', '', true);

        PDF::Ln(10);

        $date_text = $completedAt->format(' \t\h\i\s \<\i\>\<\u\>jS\<\/\u\>\<\/\i\> \d\a\y \o\f \<\i\>\<\u\>F, Y\<\/\u\>\<\/\i\>\.');
        PDF::SetFont('times', '', 15);
        PDF::MultiCell(242, 10, $date_text, 0, 'C', 0, 0, '', '', true, 20, true, true, 40, 'T');

        PDF::Ln(10);

        PDF::Image(url('pace_seal.png'), 50, 140, 37, 40, 'png', '', '', false, 520, '', false, false, 0, false, false, false);

        $cert_no    = sprintf('%04d', $seminarUser->id);
        $cert_year  = $completedAt->format('y');
        $approvals  = 'CE# SEM' . $cert_year . $cert_no;
        $approvals .= '<br>mgr@professionalonlineeducation.com<br>1-888-900-3382';

        PDF::SetFont('times', '', 11);
        PDF::MultiCell(80, 100, $approvals, 1, 'L', 0, 0, 100, 145, true, 200, true, true, 40, 'T');

        PDF::Image(url('ros_sig.jpg'), 170, 140, 79, 22, 'jpg', '', '', false, 300, '', false, false, 0, false, false, false);

        PDF::SetFont('times', '', 12);
        $txt = 'Rosalind Canham DC<br><i>Instructor</i><br/>2934 E. Avalon Drive<br/>Phoenix, AZ 85016 ';
        PDF::MultiCell(100, 10, $txt, 1, 'L', 0, 0, 175, 160, true, 20, true, true, 40, 'T');

        $mailingAddress = $user->address;
        if (!empty($user->suite)) {
            $mailingAddress .= ', ' . $user->suite;
        }
        $mailingAddress .= ', ' . $user->city . ', ' . $user->state . ' ' . $user->zipcode;
        PDF::SetFont('times', '', 9);
        PDF::MultiCell(242, 5, 'Mailing: ' . $mailingAddress, 0, 'C', 0, 0, 15, 198, true, 5, false, true, 15, 'T');

        PDF::Output('seminar-certificate.pdf', 'I');
    }
    public function myEvent($id){
        $service = new Service();
        $data = $service->MyEvent($id);
        if (($data['type'] ?? '') === 'error') {
            return redirect()->route('client_events')->with('error', $data['msg']);
        }
        return view('client.seminarDetails')->with($data);
    }



}
