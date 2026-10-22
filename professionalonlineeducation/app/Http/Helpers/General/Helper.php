<?php



namespace App\Http\Helpers\General;


use App\Models\Seminar;
use Illuminate\Http\RedirectResponse;
use App\Models\State;
use App\Models\UserQuizAnswer;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\Chapter;

class Helper{
    public static function adminUser()
    {
        return Notification::route('mail', config('settings.admin_email'));
    }


    public static function input($name,$label,$old_val,$required=false,$extra=null,$class=null,$type='text',$min=1,$max=255){
        $options = [
            'class' => "form-control $class",
            'data-rule-minlength'=>$min,
            'data-rule-maxlength'=>$max,
            'data-msg-minlength'=>__('validation.between.numeric',['attribute'=>$label,'min'=>$min,'max'=>$max]),
            'data-msg-maxlength'=>__('validation.between.numeric',['attribute'=> $label,'min'=>$min,'max'=>$max])
        ];
        if($required){
            $options['required'] ='required';
            $options['data-msg'] =__('validation.required',['attribute'=> $label]);
        }

        if($extra && is_array($extra)){
            $options = array_merge($options,$extra);
        }

        if ($type == 'password'){
            return Form::$type($name,$options);
        }else{
            return Form::$type($name, $old_val,$options);
        }

    }





    public static function select($name,$label,$data,$defaultKey=null, $required=false, $extra=null, $class=null,$showPh=true){
        $options = [
            'class' => "form-control $class",
        ];
        if($showPh){

            $options['placeholder'] =$label;
        }
        if($required){
            $options['required'] ='required';
            $options['data-msg'] =__('validation.required',['attribute'=>$label]);
        }

        if($extra && is_array($extra)){

            $options = array_merge($options,$extra);


        }
        return Form::select($name,$data,$defaultKey, $options);

    }



    /**
     * This function will add ellipsis in a string after given Limit
     * @param $str
     * @param $limit
     * @param string $end
     * @return string
     */
    public static function ellipsis($str, $limit=null, $end='...',$stripHTML=false){
        if(!$limit)
            $limit = config('settings.text_limit');

        if($stripHTML)
            $str = strip_tags($str);

        return \Illuminate\Support\Str::limit($str, $limit = $limit, $end = $end);
    }

    public static function errorHandling($exception){
        \Log::error($exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }

    /**
     * Billing / receipt email from checkout (Authorize.net + order emails).
     */
    public static function resolveBillingEmail($request, $user = null): string
    {
        $email = '';
        if ($request instanceof \Illuminate\Http\Request) {
            $email = trim((string) $request->input('email', ''));
        } elseif (is_array($request) && isset($request['email'])) {
            $email = trim((string) $request['email']);
        }

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        $user = $user ?? (auth()->check() ? auth()->user() : null);

        return $user && ! empty($user->email) ? $user->email : $email;
    }

    /**
     * @param  array  $data  Notification payload with optional 'request' + 'user'
     */
    public static function billingEmailFromNotificationData(array $data, $notifiable = null): string
    {
        $user = $data['user'] ?? $notifiable;
        if (isset($data['request'])) {
            return self::resolveBillingEmail($data['request'], $user);
        }

        return $user && ! empty($user->email) ? $user->email : '';
    }

    /**
     * Send a notification without breaking the request if mail/SMTP fails.
     */
    public static function sendNotificationSafely($notifiable, $notification, string $context = ''): bool
    {
        if ($notifiable === null) {
            return false;
        }
        try {
            Notification::send($notifiable, $notification);
            return true;
        } catch (\Throwable $e) {
            \Log::error('Notification failed'.($context ? " ({$context})" : ''), [
                'error' => $e->getMessage(),
                'context' => $context,
            ]);
            return false;
        }
    }


    public static function nameToUrl($name){
        $url = strtolower($name);
        $url = str_replace(' ','-',$url);
        $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
       return $url;
    }

    public static function getNextChapter($chapter_no){

        $model = new Chapter();
        $chapter = $model::where('chapter_no','>',$chapter_no)->orderBy('chapter_no','asc')->get();
        if ($chapter_no == $chapter[0]->chapter_no){
            $chapter = (count($chapter)>1)?$chapter[1]:$chapter[0];
        }else{
            $chapter = $chapter[0];
        }



        if ($chapter->parent_id == '0' ){
            return $chapter->id.',0';


        }else{
            return $chapter->parent_id.','.$chapter->id;
        }

    }

    public static function getCourseApprovalStates($approval,$type='string'){
   
        $states = [];
        $approvalSates = $approval->states ?? [];
      
        if (!count($approvalSates)){
             if ($type == 'string'){
            return 'None';
            }
            if ($type == 'ids'){ return []; }

        }
        foreach ($approvalSates as $approvalSate ){
            if (isset($approvalSate->state)){
                $states[$approvalSate->state->id] = $approvalSate->state->name;
            }
        }
        if ($type == 'string'){
            $str = '';
            $total = count($states);
            $i = 0;
            foreach ($states as $state){
                $i++;
                $str .= $state;
                if ($total != $i){ $str .= ', '; }
            }
            return  $str;
        }
        if ($type == 'ids'){
            return  array_keys($states);
        }

        return  $states;
    }
    public static function getCourseApprovalCategories($approval,$type='string'){
        $categories = [];

        $approvalCategories = $approval->Categories;

        if (count($approvalCategories) == 0){
            if ($type == 'string'){
            return 'None';
            }
            if ($type == 'ids'){ return []; }
        }

        foreach ($approvalCategories as $approvalCategory ){

            if (isset($approvalCategory->category)){

                $categories[$approvalCategory->category->id] = $approvalCategory->category->title;
            }
        }

        if ($type == 'string'){
            $str = '';
            $total = count($categories);
            $i = 0;

            foreach ($categories as $category){
                $i++;
                $str .= $category;
                if ($total != $i){ $str .= ', '; }
            }

            return  $str;
        }
        if ($type == 'ids'){
            return  array_keys($categories);
        }

        return  $categories;
    }


    public static function getSeminarApprovalStates($approval,$type='string'){
        $states = [];
        $approvalSates = $approval->states ?? [];
        if (!count($approvalSates)){
            if ($type == 'string'){
                return 'None';
            }
            if ($type == 'ids'){ return []; }

        }
        foreach ($approvalSates as $approvalSate ){
            if (isset($approvalSate->state)){
                $states[$approvalSate->state->id] = $approvalSate->state->name;
            }
        }
        if ($type == 'string'){
            $str = '';
            $total = count($states);
            $i = 0;
            foreach ($states as $state){
                $i++;
                $str .= $state;
                if ($total != $i){ $str .= ', '; }
            }
            return  $str;
        }
        if ($type == 'ids'){
            return  array_keys($states);
        }

        return  $states;
    }
    public static function getSeminarApprovalCategories($approval,$type='string'){
        $categories = [];

        $approvalCategories = $approval->Categories;

        if (count($approvalCategories) == 0){
            if ($type == 'string'){
                return 'None';
            }
            if ($type == 'ids'){ return []; }
        }

        foreach ($approvalCategories as $approvalCategory ){

            if (isset($approvalCategory->category)){

                $categories[$approvalCategory->category->id] = $approvalCategory->category->title;
            }
        }

        if ($type == 'string'){
            $str = '';
            $total = count($categories);
            $i = 0;

            foreach ($categories as $category){
                $i++;
                $str .= $category;
                if ($total != $i){ $str .= ', '; }
            }

            return  $str;
        }
        if ($type == 'ids'){
            return  array_keys($categories);
        }

        return  $categories;
    }

    public static function getCartCount(){
        self::prepareClientCartSessions();
        $cart = session()->get('cart');
        $cart = is_array($cart) ? $cart : [];
        $seminarCart = session()->get('seminar_cart', []);
        $n = count($cart) + (is_array($seminarCart) ? count($seminarCart) : 0);

        return $n;
    }

    /**
     * Move seminar ids from legacy session shape cart['seminar'] into seminar_cart.
     */
    public static function migrateLegacySeminarIdsIntoSeminarCart(): void
    {
        $cart = session()->get('cart');
        if (! is_array($cart) || ! isset($cart['seminar']) || ! is_array($cart['seminar'])) {
            return;
        }
        foreach ($cart['seminar'] as $sid) {
            self::appendSeminarToCart((int) $sid);
        }
        unset($cart['seminar']);
        if (isset($cart['course']) && is_array($cart['course'])) {
            session()->put('cart', $cart);
        } else {
            session()->put('cart', []);
        }
    }

    /**
     * Normalize course cart session and recover legacy seminar rows from cart['seminar'].
     */
    public static function prepareClientCartSessions(): void
    {
        self::migrateLegacySeminarIdsIntoSeminarCart();
        $cart = self::normalizeCourseCartIds(session()->get('cart'));
        session()->put('cart', $cart);
    }

    /**
     * True when $arr is a list (0..n-1 keys) — PHP 7 compatible.
     */
    protected static function isSequentialListArray(array $arr): bool
    {
        if ($arr === []) {
            return true;
        }
        $i = 0;
        foreach ($arr as $k => $_) {
            if ($k !== $i) {
                return false;
            }
            $i++;
        }

        return true;
    }

    /**
     * Append a seminar id to the session seminar cart if it exists.
     */
    public static function appendSeminarToCart(int $id): bool
    {
        if ($id < 1 || ! Seminar::whereKey($id)->exists()) {
            return false;
        }
        $cart = session()->get('seminar_cart', []);
        if (! is_array($cart)) {
            $cart = [];
        }
        if (! in_array($id, $cart, true)) {
            $cart[] = $id;
        }
        session()->put('seminar_cart', array_values($cart));

        return true;
    }

    public static function userIsClientRole($user): bool
    {
        $role = $user->roles()->first();

        return $role && (int) $role->id === 2;
    }

    /**
     * If the session has a pending seminar add (from guest intent), append to cart and redirect.
     */
    public static function redirectAfterPostLoginSeminarIfAny($user): ?RedirectResponse
    {
        $pendingSeminarId = (int) session()->pull('post_login_seminar_id', 0);
        $seminarThenCheckout = (bool) session()->pull('post_login_seminar_then_checkout', false);
        if ($pendingSeminarId < 1) {
            return null;
        }
        self::appendSeminarToCart($pendingSeminarId);
        if ($seminarThenCheckout && self::userIsClientRole($user)) {
            return redirect()->route('checkoutPage');
        }

        return redirect()->route('addToCartPage');
    }

    public static function getUserAnsByQuestionId($questionId,$userAns){
        foreach ($userAns as $ans){
            if ($ans->question_id == $questionId){
                return $ans;
            }
        }
        return new UserQuizAnswer();
    }
    public static function getUserAnsByQuestionIdCheckBox($questionId,$userAns){
        $answers = [];
        foreach ($userAns as $ans){
            if ($ans->question_id == $questionId){
                array_push($answers,$ans->answer_id);
            }
        }
        if (count($answers)){ return $answers; }
        return [];
    }
    public static function getStateIDbyName($name){
        $state = State::where('name',$name)->first();
        if (!$state){ return 0; }
        return $state->id;
    }

    /**
     * Normalize legacy cart session shapes (flat id list vs ["course"=>[ids]]).
     *
     * @param  mixed  $cart
     * @return int[]
     */
    public static function normalizeCourseCartIds($cart): array
    {
        if (! is_array($cart)) {
            return [];
        }
        if (isset($cart['course']) && is_array($cart['course'])) {
            $ids = $cart['course'];
        } elseif (self::isSequentialListArray($cart)) {
            $ids = $cart;
        } else {
            // e.g. legacy ['seminar' => [...]] without 'course' — never intval() nested arrays (that became bogus lesson id 1).
            return [];
        }

        return array_values(array_unique(array_filter(array_map('intval', $ids))));
    }

    /**
     * USD display for course prices (null-safe).
     */
    public static function formatUsd($amount): string
    {
        if ($amount === null || $amount === '') {
            return '0.00';
        }
        if (is_numeric($amount)) {
            $n = (float) $amount;
        } else {
            $n = (float) preg_replace('/[^0-9.\-]/', '', (string) $amount);
        }

        return number_format($n, 2, '.', ',');
    }

    /**
     * Plain decimal string for currency widget data-usd attributes.
     */
    public static function usdDataAmount($amount): string
    {
        if ($amount === null || $amount === '') {
            return '0.00';
        }
        if (is_numeric($amount)) {
            $n = (float) $amount;
        } else {
            $n = (float) preg_replace('/[^0-9.\-]/', '', (string) $amount);
        }

        return number_format($n, 2, '.', '');
    }
    
    public static function courseHrTag($course){

        $categories = [];
        foreach ($course->categories as $cat){
            $categories[] = $cat->category_id;
        }
        $ceCats = [1,3,4];

        if (in_array($categories[0],$ceCats)){
            return 'CE';
        }
        return 'Hours';
    }

    public static function checkApprovalValidity($course_completion_date, $end_date){//this function will check that at the time of user course completion approval were valid or not
        return (strtotime($course_completion_date) <= strtotime($end_date)) ? true : false;
    }

    public static function  checkApprovalValidity2($course_completion_date, $start_date){//this function will check provided date is not a future date
        return (strtotime($course_completion_date) >= strtotime($start_date)) ? true : false;
    }
}

