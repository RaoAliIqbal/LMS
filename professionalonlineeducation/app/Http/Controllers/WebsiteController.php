<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CourseApproval;
use App\Models\CourseApprovalCategory;
use App\Models\CourseApprovalState;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\Regulation;
use App\Models\Seminar;
use App\Models\DiscountCoupon;
use App\Models\State;
use App\Services\EducationService as Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\BlogService;
use App\Services\EducationService;
use App\Services\WebinarService;
use App\Http\Helpers\General\Helper;
use App\Mail\ChatbotInternalPriorityMail;
use App\Mail\ChatbotStaffNotificationMail;
use App\Mail\ChatbotStudentReplyMail;
use App\Models\ChatEscalation;
use App\Models\ChatbotQa;
use App\Support\ChatbotMailAddresses;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class WebsiteController extends Controller
{

    public function index()
    {
        try {
            $courses = collect();
            if (Schema::hasTable('lessons')) {
                $courses = Lesson::query()
                    ->select('id', 'title', 'title_two', 'image', 'price', 'hours')
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();
            }

            $paceCodes = ['AL','AK','AR','AZ','CO','DE','IA','ID','IL','KS','KY','MA','MD','ME','MN','MT','NE','ND','OH','OR','PA','RI','SD','WY'];
            $paceStatePills = [];
            foreach ($paceCodes as $code) {
                $row = State::where('slug', strtoupper($code))->first();
                $paceStatePills[] = [
                    'code' => $code,
                    'href' => $row ? route('statesCategories', strtolower($row->slug)) : null,
                ];
            }

            return view('web.index', compact('courses', 'paceStatePills'));
        } catch (\Throwable $e) {
            \Log::error('Homepage failed to load', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            return view('web.index', [
                'courses' => collect(),
                'paceStatePills' => [],
            ]);
        }
    }

    public function about()
    {
        return view('web.about');
    }

    /**
     * Become an Arizona Chiropractic Assistant landing page.
     */
    public function arizonaChiropracticAssistant()
    {
         dd('Reached');
        return view('web.arizonaChiropracticAssistant');
    }

    public function refundPolicy()
    {
        return view('web.refundPolicy');
    }

    public function privacyPolicy()
    {
        return view('web.privacyPolicy');
    }

    public function termsConditions()
    {
        return view('web.termsConditions');
    }

    public function cookiesPolicy()
    {
        return view('web.cookiesPolicy');
    }

    public function getStateIdByName($state){
        $state = State::where('name', $state)->first();
        if (!$state){ return 1; }
        return $state->id;
    }
    public function courses(Request $request){
        // User Data
        $userStateId = null;
        $userCategoryId = null;
//dd($request->all());
        if (auth()->user()){
            if (auth()->user()->category_id != null && !$request->category_id){
                $request->request->add(['category_id' => auth()->user()->category_id]);
            }
            if ($request->state_id == null){
                $request->request->add(['state_id' => $this->getStateIdByName(auth()->user()->state)]);
            }

        }
        if ($request->category_id){
            $request->request->add(['category_id' => $request->category_id]);
        }

        if (! $request->filled('state_id')) {
            $browse = (int) session('browse_state_id', 0);
            if ($browse > 0 && State::whereKey($browse)->exists()) {
                if ($request->query('state_id') === null) {
                    return redirect()->route('coursesPage', array_filter([
                        'state_id' => $browse,
                        'category_id' => $request->query('category_id'),
                        'search' => $request->query('search'),
                    ], function ($v) {
                        return $v !== null && $v !== '';
                    }));
                }
                $request->merge(['state_id' => $browse]);
            }
        }

        // States
        $stateIds = $this->getApprovedStateIds();
        $states = State::whereIn('id',$stateIds)->get();


        //Category
        $categoryIds = $this->getApprovedCategoryIds();
        $categories = Category::whereIn('id',$categoryIds)->orderby('sort_by','ASC')->get();
        $courses = Lesson::select('*');
        $ids = $this->getApprovedCourseIds($request);
//        dd($request->all());
        if ($request->search){
            $search = $request->search;
            $courses->where('title', 'LIKE', '%'.$search.'%');
        }
        if ($request->filled('state_id')){
            $courses = $courses->whereIn('id',$ids)->get();
        }else{
            $courses = [];
        }
        $regulations = [];

        $category = new Category;
        return view('web.courses',compact('courses','states','categories','request','regulations','category'));
    }
    public function events(){
        $seminars = Seminar::get();
        return view('web.seminars',compact('seminars'));
    }
    public function eventDetails($id){
        $seminar = Seminar::find($id);
        if (!$seminar){ return abort('404');exit(); }
        return view('web.seminar',compact('seminar'));
    }

    public function states()
    {
        $ids = $this->getApprovedStateIds();
        $states = State::whereIn('id',$ids)->orderBy('name','asc')->get();
        return view('web.states',compact('states'));
    }
    public function statesCategories($slug){
     //    dd(12);
        $state = State::where('slug',strtoupper($slug))->first();
        if (!$state){ return abort('404');exit(); }
        session(['browse_state_id' => (int) $state->id]);
        $approvalsIds = CourseApprovalState::where('state_id',$state->id)->pluck('course_approval_id');
        $categoryIds = CourseApprovalCategory::whereIn('course_approval_id',$approvalsIds)->pluck('category_id');
        $categories = Category::whereIn('id',$categoryIds)->orderBy('sort_by','asc')->get();
        return view('web.categories',compact('categories','state'));
    }
    public function coursesByStateAndCategory(Request $request, $slug, $categorySlug){
        //dd(1);
        if($slug == 'AZ' && $categorySlug == '2'){
             return redirect()->route('statesCategories','az');
        }
        // getting state and category
        $state = State::where('slug',strtoupper($slug))->first();
        if (!$state){ return abort('404');exit(); }
        session(['browse_state_id' => (int) $state->id]);
        $category = Category::where('slug',$categorySlug)->first();
        if (!$category){ return abort('404');exit(); }
        $stateApprovalIds = $this->getApprovalIdsByState($state->id);
        $categoryApprovalIds = $this->getApprovalIdsByCategory($category->id);
        $approvalIds = array_intersect($stateApprovalIds,$categoryApprovalIds);
        $courseIds = array_unique($this->getCourseIdsByApprovalIds($approvalIds));
        $courses = Lesson::whereIn('id',$courseIds)->get();
        $regulations = Regulation::where(['state_id'=>$state->id,'category_id'=>$category->id])->get();

        $stateIds = $this->getApprovedStateIds();
        $states = State::whereIn('id', $stateIds)->orderBy('name', 'asc')->get();
        $categoryIds = CourseApprovalCategory::whereIn('course_approval_id', $stateApprovalIds)->pluck('category_id');
        $categories = Category::whereIn('id', $categoryIds)->orderBy('sort_by', 'asc')->get();

        return view('web.courses', compact('courses', 'category', 'state', 'regulations', 'request', 'categories', 'states'));
    }
    public function getApprovedStateIds(){
        return CourseApprovalState::groupBy('state_id')->pluck('state_id');
    }
    public function getApprovedCategoryIds(){
        return CourseApprovalCategory::groupBy('category_id')->pluck('category_id');
    }

    public function getApprovalIdsByState($stateId){
        return CourseApprovalState::where('state_id',$stateId)->groupBy('course_approval_id')->pluck('course_approval_id')->toArray();
    }
    public function getApprovalIdsByCategory($categoryId){
        return CourseApprovalCategory::where('category_id',$categoryId)->groupBy('course_approval_id')->pluck('course_approval_id')->toArray();
    }
    public function getCourseIdsByApprovalIds($approvalIds){
        return CourseApproval::whereIn('id',$approvalIds)->pluck('lesson_id')->toArray();
    }
    public function getApprovedCourseIds($request){
        $CourseApproval = CourseApproval::select('*');
        if (isset($request->category_id) && !isset($request->state_id)){
            $categoryApprovalIDs = CourseApprovalCategory::where('category_id',$request->category_id)->groupBy('course_approval_id')->pluck('course_approval_id')->toArray();
            $CourseApproval->whereIn('id',$categoryApprovalIDs);
        }
        if (isset($request->state_id) && !isset($request->category_id)){
            $stateApprovalIDs = CourseApprovalState::where('state_id',$request->state_id)->groupBy('course_approval_id')->pluck('course_approval_id')->toArray();
            $CourseApproval->whereIn('id',$stateApprovalIDs);
        }
        if (isset($request->state_id) && isset($request->category_id)){
            $categoryApprovalIDs = CourseApprovalCategory::where('category_id',$request->category_id)->groupBy('course_approval_id')->pluck('course_approval_id')->toArray();
            $stateApprovalIDs = CourseApprovalState::where('state_id',$request->state_id)->groupBy('course_approval_id')->pluck('course_approval_id')->toArray();
            $approvalIds = array_intersect($categoryApprovalIDs,$stateApprovalIDs);
            $CourseApproval->whereIn('id',$approvalIds);
        }
        return $CourseApproval->pluck('lesson_id')->toArray();
    }

    public function courseDetailsPage($id){
        $course = Lesson::find($id);
        if (!$course){ return abort('404');exit(); }
        return view('web.course_details',compact('course'));
    }


    public function upload(Request $request){
        $fileName=$request->file('file')->getClientOriginalName();
        $path=$request->file('file')->storeAs('uploads', $fileName, 'public');


        if ($file = $request->file('file')) {

            $memeType = $file->getMimeType();
            $path = "uploads/courses";
            if(!is_dir($path)){
                if (!mkdir($path, 0777, true) && !is_dir($path)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
                }
            }
            $name = rand(0,9999).$file->getClientOriginalName();
            $file->move($path, $name);
        return response()->json(['location'=>'/'.$path.'/'.$name]);
        }

        return false;
    }

    public function addToCartBk(Request $request){
        $courseId = $request->courseId;
        $count = 0;
        $cart = $this->addToCartSession($courseId);
        if (array_key_exists('course',$cart)){
            $count = $count+count($cart['course']);
        }
        if (array_key_exists('seminar',$cart)){
            $count = $count+count($cart['seminar']);
        }
        return $count;
    }
    public function addToCartSessionBk($id,$type='course')
    {
        $cart = session()->get('cart');
//        session()->forget('cart');

        if ($cart == null){
            if ($type == 'course'){
                $cart = ['course'=>[$id]];
            }
            if ($type == 'seminar'){
                $cart = ['seminar'=>[$id]];
            }
        }else{
            if (!in_array($id,$cart[$type])){
                array_push($cart[$type],$id);
            }
        }

        // IF Cart is empty Add First Product
        session()->put('cart', $cart);
        return $cart;
    }

    public function addToCart(Request $request){
        $courseId = (int) $request->input('courseId', 0);
        if ($courseId < 1 || ! Lesson::whereKey($courseId)->exists()) {
            return response('0', 422);
        }
        $this->addToCartSession($courseId);

        return (string) $this->combinedCartCount();
    }

    public function addToCartSeminar(Request $request)
    {
        $id = (int) $request->input('seminarId');
        if (! Helper::appendSeminarToCart($id)) {
            return response('0', 422);
        }

        return (string) $this->combinedCartCount();
    }

    /**
     * Guest: store seminar id and send to login; logged-in: add to cart and continue.
     */
    public function seminarAddToCartLoginIntent($id)
    {
        $id = (int) $id;
        if ($id < 1 || ! Seminar::whereKey($id)->exists()) {
            abort(404);
        }

        if (auth()->check()) {
            Helper::appendSeminarToCart($id);
            if (Helper::userIsClientRole(auth()->user())) {
                return redirect()->route('checkoutPage');
            }

            return redirect()->route('addToCartPage');
        }

        session([
            'post_login_seminar_id' => $id,
            'post_login_seminar_then_checkout' => true,
        ]);

        return redirect()->route('login');
    }

    protected function combinedCartCount(): int
    {
        Helper::prepareClientCartSessions();
        $c = session()->get('cart');
        $c = is_array($c) ? $c : [];
        $s = session()->get('seminar_cart', []);

        return count($c) + (is_array($s) ? count($s) : 0);
    }

    protected function getSeminarCartTotal($seminars): float
    {
        $total = 0;
        foreach ($seminars as $seminar) {
            $total += (float) $seminar->price_for_display;
        }

        return $total;
    }
    public function addToCartSession($id)
    {
        Helper::prepareClientCartSessions();
        $cart = session()->get('cart');
        $cart = is_array($cart) ? $cart : [];
        $id = (int) $id;
        if ($id > 0 && ! in_array($id, $cart, true)) {
            $cart[] = $id;
        }
        session()->put('cart', $cart);

        return $cart;
    }



    public function addToCartPage(){
        Helper::prepareClientCartSessions();
        $cart = session()->get('cart');
        $cart = is_array($cart) ? $cart : [];
        $seminarCart = session()->get('seminar_cart', []);
        if (! is_array($seminarCart)) {
            $seminarCart = [];
        }

        $courses = [];
        if (count($cart)) {
            $courses = Lesson::whereIn('id', $cart)->get();
        }
        $seminars = [];
        if (count($seminarCart)) {
            $seminars = Seminar::whereIn('id', $seminarCart)->orderBy('id')->get();
        }

        return view('web.addToCart', compact('courses', 'seminars'));
    }
    public function removeCart($id){
        Helper::prepareClientCartSessions();
        $cart = session()->get('cart');
        if (! is_array($cart)) {
            $cart = [];
        }
        if (($key = array_search($id, $cart)) !== false) {
            unset($cart[$key]);
        }
        session()->put('cart', array_values($cart));
        if (count($cart)) {
            return redirect()->route('addToCartPage')->with('info', 'Course Successfully removed');
        }
        $seminarCart = session()->get('seminar_cart', []);
        if (is_array($seminarCart) && count($seminarCart)) {
            return redirect()->route('addToCartPage')->with('info', 'Course Successfully removed');
        }

        return redirect()->route('coursesPage');

    }

    public function removeSeminarFromCart($id)
    {
        $id = (int) $id;
        $cart = session()->get('seminar_cart', []);
        if (! is_array($cart)) {
            $cart = [];
        }
        if (($key = array_search($id, $cart, true)) !== false) {
            unset($cart[$key]);
        }
        session()->put('seminar_cart', array_values($cart));
        Helper::prepareClientCartSessions();
        $lessonCart = session()->get('cart');
        $lessonCart = is_array($lessonCart) ? $lessonCart : [];
        if (count($lessonCart) || count($cart)) {
            return redirect()->route('addToCartPage')->with('info', 'Item removed from your cart.');
        }

        return redirect()->route('eventsPage');
    }

    public function getEnrolledCourseInCart($cart,$userCourses){

        $courses = [];
        foreach ($cart as $c){
            if (in_array($c,$userCourses)){
                $courses[] = $c;
            }
        }
        return $courses;
    }
    public function checkoutPage(){
        Helper::prepareClientCartSessions();
        $cart = session()->get('cart');
        $cart = is_array($cart) ? $cart : [];
        $seminarCart = session()->get('seminar_cart', []);
        if (! is_array($seminarCart)) {
            $seminarCart = [];
        }
        $seminarCart = array_values(array_filter($seminarCart));

        if (count($cart) === 0 && count($seminarCart) === 0) {
            return redirect()->route('coursesPage');
        }
        $cart = array_values(array_filter($cart));

        $states = State::all();
        $courses = [];
        $seminars = [];

        if (count($cart)) {
            if (auth()->user()) {
                $user = auth()->user();
                $userCourses = LessonUser::where('user_id', $user->id)->whereYear('created_at', date('Y'))->pluck('lesson_id')->toArray();
                $notEnrolledCourses = array_diff($cart, $userCourses);
                $EnrolledCoursesInCart = $this->getEnrolledCourseInCart($cart, $userCourses);
                if (count($EnrolledCoursesInCart)) {
                    Session::flash('info', 'This course already exist in your account. Please select any other course');
                }

                $courses = Lesson::whereIn('id', $notEnrolledCourses)->get();

                session()->put('cart', array_values($notEnrolledCourses));
                $cart = array_values($notEnrolledCourses);
            } else {
                $courses = Lesson::whereIn('id', $cart)->get();
            }
        }

        if (count($seminarCart)) {
            $seminars = Seminar::whereIn('id', $seminarCart)->orderBy('id')->get();
        }

        if (count($courses) === 0 && count($seminars) === 0) {
            return redirect()->route('coursesPage');
        }

        $total = $this->getTotalAmount($courses) + $this->getSeminarCartTotal($seminars);
        $ageVerification = false;
        $ageRestrictedCourses = ['50', '38'];
        foreach ($cart as $item) {
            if (in_array((string) $item, $ageRestrictedCourses, true) || in_array($item, $ageRestrictedCourses)) {
                $ageVerification = true;
            }
        }

        $courses = collect($courses);
        $seminars = collect($seminars);

        return view('web.checkoutPage', compact('courses', 'seminars', 'states', 'total', 'ageVerification'));
    }
    public function getTotalAmount($courses){
        $totalAmount = 0;
        foreach ($courses as $course){
            $totalAmount += (float) $course->price;
        }
        return $totalAmount;
    }
        public function applyDiscount(Request $request){
            try {
            if ($request->code == 'CECA5o') {
                if (auth()->check()) {
                    $last_course = auth()->user()->courses->last();
                    if ($last_course) {
                        $course = Lesson::find($last_course->id);
                        $evaluation = $last_course->evaluation;
                        if ($course && $evaluation && $evaluation->title) {
                            $title = $evaluation->title;

                            // Check if title contains 'Initial CA' or 'Initial CE' (anywhere in string)
                            $hasInitialCA = stripos($title, 'Initial CA') !== false;
                            $hasInitialCE = stripos($title, 'CE') !== false;

                            // Make sure only one of them exists
                            if ($hasInitialCA xor $hasInitialCE) {
                                $pivotPrice = $last_course->pivot->price ?? null;
                                if ($pivotPrice !== null && (float) $pivotPrice != (float) $course->price) {
                                    $courseIdsRaw = $request->input('courses');
                                    $courseIds = is_array($courseIdsRaw)
                                        ? $courseIdsRaw
                                        : json_decode((string) $courseIdsRaw, true);
                                    if (! is_array($courseIds)) {
                                        $courseIds = [];
                                    }
                                    $new_courses = Lesson::whereIn('id', $courseIds)->latest()->get();

                                    foreach ($new_courses as $newCourse) {
                                        $newEval = $newCourse->evaluation;
                                        if (! $newEval || ! $newEval->title) {
                                            continue;
                                        }
                                        $new_title = $newEval->title;

                                        $newHasInitialCA = stripos($new_title, 'Initial CA') !== false;
                                        $newHasInitialCE = stripos($new_title, 'CE') !== false;

                                        if ($newHasInitialCA xor $newHasInitialCE) {
                                            $discount = $this->getDiscount($request->code, $newCourse->price);
                                            $total = $request->total;
                                            $newTotal = $request->total - $discount['newTotal'];
                                            $appliedDiscount = true;

                                            return view('web.partials.summeryBlock', compact('total', 'newTotal', 'appliedDiscount'));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // Fallback if discount not applied
                $discount = [
                    'total' => $request->total,
                    'newTotal' => $request->total,
                ];
            } else {
                // Normal discount flow
                $discount = $this->getDiscount($request->code, $request->total);
                if ($discount === false) {
                    $total = (float) $request->input('total', 0);
                    $newTotal = $total;
                    $appliedDiscount = false;

                    return view('web.partials.summeryBlock', compact('total', 'newTotal', 'appliedDiscount'))
                        ->with('error', 'Invalid or inactive coupon code.');
                }
            }

      $total = $discount['total'];
      $newTotal = $discount['newTotal'];
      $appliedDiscount = ($newTotal < $total);
        return view('web.partials.summeryBlock',compact('total','newTotal','appliedDiscount'));
            } catch (\Throwable $e) {
                \Log::error('applyDiscount failed', ['error' => $e->getMessage()]);
                $total = (float) $request->input('total', 0);
                $newTotal = $total;
                $appliedDiscount = false;
                return view('web.partials.summeryBlock', compact('total', 'newTotal', 'appliedDiscount'))
                    ->with('error', 'Discount could not be applied. Please try again or call 1-888-900-3382.');
            }
    }
    public function getDiscount($code,$reqTotal){
        $discount = DiscountCoupon::findByCode($code);

        $total = $reqTotal;
        $newTotal = $total;

        if ($code == 'RLC239'){
            $user = auth()->user();
            if (! $user) {
                return false;
            }
            $userCreatedAt = Carbon::parse($user->created_at);
            $now = Carbon::now();
            if ($now->diffInHours($userCreatedAt) >= 12){
                return false;
            }
        }

        if (! $discount) {
            return false;
        }

        if ($discount->type == 'flat'){
            $newTotal = max(0, $total - $discount->dicount);
        } else {
            $off = 100 - $discount->dicount;
            $off = $off / 100;
            $newTotal = max(0, $off * $total);
        }

        return ['total'=>$total, 'newTotal'=>$newTotal];
    }
    public function seminarCheckoutPage($id)
    {
        $states = State::all();
        $seminar = Seminar::find($id);
        if (!$seminar){ return abort('404');exit(); }
        return view('web.seminarCheckout',compact('seminar','states'));
    }


    public function blog(Request $request,$catSlug=false)
    {
        return redirect()->to('admin');
        $service = new BlogService();
        $getData = $service->blog_lists($catSlug,$request->search);
        $posts = $getData['data'];
        $title = $getData['title'];
        return view('web.blog',compact('posts','title','request'));
    }
    public function blogDetails($slug){
        $service = new BlogService();
        $getData = $service->postPage($slug);

        if ($getData['type'] == 'error'){
            return abort('404');exit();
        }

        return view('web.blogDetails')->with($getData);
    }
    public function investorEducation(Request $request)
    {
        $service = new EducationService();
        $getData = $service->LessonList($request->search);
        $lessons = $getData['data'];
        return view('web.investorEducation',compact('lessons'));

    }
    public function educationDetails($id){

        $service = new EducationService();
        $getData = $service->coursePage($id);

        if ($getData['type'] == 'error'){
            return abort('404');exit();
        }


        return view('web.course')->with($getData);


    }

    /**
     * Website chatbot — store escalation and notify the correct staff inbox.
     */
    public function chatbotEscalate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:40',
                'course_name' => 'required|string|max:255',
                'issue' => 'nullable|string|max:8000',
                'type' => 'required|in:it,instructor,customer_service',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Please check the form and try again.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();

            ChatbotMailAddresses::warnIfMailDriverCannotDeliver();

            try {
                $escalation = $this->createChatEscalationForChatbot($data);
            } catch (QueryException $e) {
                \Log::error('chatbotEscalate DB: '.$e->getMessage(), ['code' => $e->getCode()]);
                $mgr = $this->chatbotManagerInbox();
                try {
                    $phone = isset($data['phone']) ? $data['phone'] : '';
                    $issue = isset($data['issue']) ? $data['issue'] : '';
                    $body = "Website chatbot — the request could not be saved to the database (run migrations / check chat_escalations columns).\r\n\r\n".
                        "Name: {$data['name']}\r\n".
                        "Email: {$data['email']}\r\n".
                        "Phone: {$phone}\r\n".
                        "Course name: {$data['course_name']}\r\n".
                        "Team selected: {$data['type']}\r\n".
                        "Issue / message:\r\n{$issue}\r\n";
                    Mail::raw($body, function ($message) use ($mgr, $data) {
                        $from = ChatbotMailAddresses::envelopeFrom();
                        $cfg = config('settings.chatbot', []);
                        $fromName = $cfg['from_name_escalations'] ?? 'Support Bot Escalations';
                        $message->from($from, $fromName)
                            ->replyTo($data['email'], ChatbotMailAddresses::sanitizeDisplayName($data['name']))
                            ->to($mgr)
                            ->subject('[Chatbot] Support form (database save failed)');
                    });
                } catch (\Throwable $mailEx) {
                    \Log::error('chatbotEscalate fallback mgr mail failed: '.$mailEx->getMessage(), ['to' => $mgr]);
                }

                return response()->json([
                    'ok' => false,
                    'message' => 'We could not save your request right now. Our team has been alerted — please call 1-888-900-3382 or try again in a few minutes.',
                ], 503);
            }

            $staffNotified = \App\Support\ChatbotMailSender::notifyStaff($escalation);

            if (! $staffNotified) {
                \Log::error('chatbot: form saved but email to manager failed', [
                    'escalation_id' => $escalation->id,
                    'manager_inbox' => \App\Support\ChatbotMailAddresses::staffInbox(),
                    'envelope_from' => \App\Support\ChatbotMailAddresses::envelopeFrom(),
                    'mail_driver' => config('mail.default'),
                ]);
            }

            return response()->json([
                'ok' => true,
                'staff_notified' => $staffNotified,
                'message' => 'Your request has been submitted. We will get back to you within 24 hours.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('chatbotEscalate: '.$e->getMessage(), ['exception' => get_class($e)]);

            return response()->json([
                'ok' => false,
                'message' => 'We could not process your request right now. Please call 1-888-900-3382 or try again in a few minutes.',
            ], 500);
        }
    }

    /**
     * Student opened a link in the 48-hour follow-up email — show a confirmation page.
     * Read-only on purpose: prefetchers / Outlook Safe Links / Gmail proxy auto-click GETs,
     * so any state mutation lives behind the POST handler below.
     */
    public function chatbotFollowUp(string $token, string $outcome)
    {
        [$escalation, $key, $errorView] = $this->resolveChatbotFollowUpRequest($token, $outcome);
        if ($errorView !== null) {
            return $errorView;
        }

        $labels = [
            'yes'        => 'Yes — my issue is resolved',
            'needs-help' => 'I still need help',
            'no-help'    => 'No, I do not need further help',
        ];

        return view('web.chatbotFollowUpConfirm', [
            'escalation'   => $escalation,
            'token'        => $token,
            'outcome'      => $key,
            'outcomeLabel' => $labels[$key],
        ]);
    }

    /**
     * Student confirmed their follow-up response — perform the state change + send mail.
     */
    public function chatbotFollowUpConfirm(Request $request, string $token, string $outcome)
    {
        [$escalation, $key, $errorView] = $this->resolveChatbotFollowUpRequest($token, $outcome);
        if ($errorView !== null) {
            return $errorView;
        }

        $staffTo = $this->chatbotManagerInbox();
        $issueLabel = $escalation->typeLabel();

        DB::beginTransaction();
        try {
            if ($key === 'yes') {
                $escalation->status = 'resolved';
                $escalation->save();
                Mail::to($escalation->email)->send(new ChatbotStudentReplyMail($escalation, 'great_news'));
                DB::commit();

                return view('web.chatbotFollowUpThanks', [
                    'message' => 'Thanks for confirming. We are glad you are all set. If anything else comes up, you can reach us anytime through Support on the website.',
                ]);
            }

            if ($key === 'needs-help') {
                $escalation->status = 'needs_help';
                $escalation->save();
                Mail::to($escalation->email)->send(new ChatbotStudentReplyMail($escalation, 'on_it'));
                Mail::to($staffTo)->send(new ChatbotInternalPriorityMail($escalation, $issueLabel));
                DB::commit();

                return view('web.chatbotFollowUpThanks', [
                    'message' => 'We have flagged your request as a priority. A team member will reach out by email shortly.',
                ]);
            }

            // no-help → administration-style notice to student only (per client copy)
            $escalation->status = 'no_help_needed';
            $escalation->save();
            Mail::to($escalation->email)->send(new ChatbotStudentReplyMail($escalation, 'escalated'));
            DB::commit();

            return view('web.chatbotFollowUpThanks', [
                'message' => 'Thank you for the feedback. Our team has recorded your response.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('chatbotFollowUpConfirm failed: '.$e->getMessage(), [
                'id'        => $escalation->id,
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'previous'  => $e->getPrevious() ? $e->getPrevious()->getMessage() : null,
            ]);

            return view('web.chatbotFollowUpInvalid');
        }
    }

    /**
     * Validate token + outcome shared by the GET (confirmation page) and POST (apply)
     * handlers. Returns [$escalation, $key, $errorView]; $errorView is non-null when
     * the request should short-circuit to the Invalid / Already view.
     *
     * @return array{0: \App\Models\ChatEscalation|null, 1: string|null, 2: \Illuminate\Contracts\View\View|null}
     */
    protected function resolveChatbotFollowUpRequest(string $token, string $outcome): array
    {
        try {
            $escalation = ChatEscalation::findByPublicToken($token);
        } catch (QueryException $e) {
            \Log::error('chatbotFollowUp lookup DB: '.$e->getMessage());

            return [null, null, view('web.chatbotFollowUpInvalid')];
        }

        if (! $escalation) {
            return [null, null, view('web.chatbotFollowUpInvalid')];
        }

        if (! $escalation->follow_up_sent_at) {
            return [null, null, view('web.chatbotFollowUpInvalid')];
        }

        if ($escalation->status !== 'pending') {
            return [null, null, view('web.chatbotFollowUpAlready')];
        }

        $normalized = str_replace('_', '-', strtolower($outcome));
        $map = [
            'yes'        => 'yes',
            'needs-help' => 'needs-help',
            'needshelp'  => 'needs-help',
            'no-help'    => 'no-help',
            'nohelp'     => 'no-help',
        ];
        $key = isset($map[$normalized]) ? $map[$normalized] : null;
        if ($key === null) {
            return [null, null, view('web.chatbotFollowUpInvalid')];
        }

        return [$escalation, $key, null];
    }

    /**
     * Public JSON: extra troubleshooting tips per topic (managed in admin → Support Chatbot Q&A).
     */
    public function chatbotQaJson()
    {
        $tips = [];
        try {
            $rows = ChatbotQa::query()
                ->where('is_active', true)
                ->orderBy('topic_key')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(['topic_key', 'tip_html']);
            foreach ($rows as $row) {
                if (! isset($tips[$row->topic_key])) {
                    $tips[$row->topic_key] = [];
                }
                $tips[$row->topic_key][] = $row->tip_html;
            }
        } catch (\Throwable $e) {
            \Log::warning('chatbotQaJson: '.$e->getMessage());
        }

        return response()->json(['tips' => $tips]);
    }

    /**
     * Insert chat escalation using only columns that exist (avoids 503 when migrations lag behind).
     * If the course_name column is missing, the course is prepended to issue for storage; the returned
     * model has course_name set in memory so staff email still shows the course row.
     */
    protected function createChatEscalationForChatbot(array $data): ChatEscalation
    {
        if (! Schema::hasTable('chat_escalations')) {
            throw new \RuntimeException('chat_escalations table does not exist');
        }

        $cols = Schema::getColumnListing('chat_escalations');

        /** @return string|null physical column name as returned by the DB (casing preserved) */
        $col = function (string $logical) use ($cols) {
            foreach ($cols as $actual) {
                if (strcasecmp((string) $actual, $logical) === 0) {
                    return (string) $actual;
                }
            }

            return null;
        };

        $payload = [];

        $nameCol = $col('name');
        if ($nameCol !== null) {
            $payload[$nameCol] = $data['name'];
        }
        $emailCol = $col('email');
        if ($emailCol !== null) {
            $payload[$emailCol] = $data['email'];
        }
        $phoneCol = $col('phone');
        if ($phoneCol !== null) {
            $payload[$phoneCol] = isset($data['phone']) ? $data['phone'] : null;
        }
        $typeCol = $col('type');
        if ($typeCol !== null) {
            $payload[$typeCol] = $data['type'];
        }

        $course = $data['course_name'];
        $issue = isset($data['issue']) ? $data['issue'] : null;

        $courseNameCol = $col('course_name');
        if ($courseNameCol !== null) {
            $payload[$courseNameCol] = $course;
        }

        $issueCol = $col('issue');
        if ($issueCol !== null) {
            if ($courseNameCol === null) {
                $prefix = 'Course: '.$course."\n\n";
                $issue = ($issue === null || $issue === '') ? rtrim($prefix) : $prefix.$issue;
            }
            $payload[$issueCol] = $issue;
        }

        $rand = Str::random(48);
        $tokenCol = $col('token');
        $followUpTokenCol = $col('follow_up_token');
        if ($tokenCol !== null && $followUpTokenCol !== null && strcasecmp($tokenCol, $followUpTokenCol) !== 0) {
            $payload[$tokenCol] = $rand;
            $payload[$followUpTokenCol] = Str::random(48);
        } elseif ($tokenCol !== null) {
            $payload[$tokenCol] = $rand;
        } elseif ($followUpTokenCol !== null) {
            $payload[$followUpTokenCol] = $rand;
        }

        $statusCol = $col('status');
        if ($statusCol !== null) {
            $payload[$statusCol] = 'pending';
        }

        $now = now();
        $createdAtCol = $col('created_at');
        $updatedAtCol = $col('updated_at');
        if ($createdAtCol !== null) {
            $payload[$createdAtCol] = $now;
        }
        if ($updatedAtCol !== null) {
            $payload[$updatedAtCol] = $now;
        }

        // Use Query Builder with physical keys — Eloquent::create() drops keys not matching $fillable
        // exactly (case-sensitive), so mixed-case DB columns (e.g. Follow_Up_Token) never got saved.
        $id = DB::table('chat_escalations')->insertGetId($payload);

        // Prefer write connection: read replicas can lag and make find() miss the row right after insert.
        $escalation = ChatEscalation::onWriteConnection()->find($id);
        if (! $escalation) {
            usleep(100000);
            $escalation = ChatEscalation::onWriteConnection()->find($id);
        }
        if (! $escalation) {
            $row = DB::table('chat_escalations')->where('id', $id)->first();
            if ($row) {
                $escalation = new ChatEscalation();
                $escalation->exists = true;
                $escalation->setRawAttributes((array) $row, true);
            }
        }
        if (! $escalation) {
            throw new \RuntimeException('chat_escalation row missing after insert (id='.$id.')');
        }

        if ($courseNameCol === null) {
            $escalation->setAttribute('course_name', $course);
        }

        return $escalation;
    }

    /**
     * Fixed manager inbox — all chatbot staff notifications (form, follow-up priority, DB fallback mail).
     */
    protected function chatbotManagerInbox(): string
    {
        return \App\Support\ChatbotMailAddresses::staffInbox();
    }
}