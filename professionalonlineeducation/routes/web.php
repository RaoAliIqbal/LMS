<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Website links



Route::get('/state/AZ/category/2', function(){
    return redirect()->route('statesCategories','az');
});

// Website links — homepage MUST stay on WebsiteController (never login)

Route::get('/become-arizona-chiropractic-assistant', function () {
    return view('web.arizona-chiropractic-assistant');
});
Route::get('/', 'WebsiteController@index')->name('home');
Route::get('/home', 'WebsiteController@index')->name('home_');
Route::get('/about', 'WebsiteController@about')->name('about');
Route::get('/refund-policy', 'WebsiteController@refundPolicy')->name('refundPolicy');
Route::get('/privacy-policy', 'WebsiteController@privacyPolicy')->name('privacyPolicy');
Route::get('/terms-and-conditions', 'WebsiteController@termsConditions')->name('termsConditions');
Route::get('/cookies-policy', 'WebsiteController@cookiesPolicy')->name('cookiesPolicy');
Route::get('/states', 'WebsiteController@states')->name('states');
Route::get('/state/{slug}/', 'WebsiteController@statesCategories')->name('statesCategories');
Route::get('/state/{slug}/{categorySlug}', 'WebsiteController@coursesByStateAndCategory')->name('coursesByStateAndCategory');
Route::get('/course/{id}', 'WebsiteController@courseDetailsPage')->name('courseDetailsPage');
Route::get('/courses', 'WebsiteController@courses')->name('coursesPage');
Route::get('/events', 'WebsiteController@events')->name('eventsPage');
Route::get('/event', 'WebsiteController@events')->name('eventsPages');
Route::get('/event/add-to-cart-intent/{id}', 'WebsiteController@seminarAddToCartLoginIntent')->name('seminarAddToCartLoginIntent');
Route::get('/event/{id}', 'WebsiteController@eventDetails')->name('eventDetails');
Route::post('/add-to-cart', 'WebsiteController@addToCart')->name('addToCart');
Route::post('/add-to-cart/seminar', 'WebsiteController@addToCartSeminar')->name('addToCartSeminar');
Route::get('/add-to-cart', 'WebsiteController@addToCartPage')->name('addToCartPage');
Route::get('/remove-cart/{id}', 'WebsiteController@removeCart')->name('removeCart');
Route::get('/remove-seminar-cart/{id}', 'WebsiteController@removeSeminarFromCart')->name('removeSeminarCart');
Route::post('apply-discount', 'WebsiteController@applyDiscount')->name('applyDiscount');

// Chatbot routes
Route::get('/chatbot/qa.json', 'WebsiteController@chatbotQaJson')
    ->middleware('throttle:60,1')->name('chatbot.qa.json');
Route::post('/chatbot/escalate', 'WebsiteController@chatbotEscalate')
    ->middleware('throttle:20,1')->name('chatbot.escalate');
Route::get('/chatbot/followup/{token}/{outcome}', 'WebsiteController@chatbotFollowUp')
    ->middleware('throttle:60,1')->name('chatbot.followup');
Route::post('/chatbot/followup/{token}/{outcome}', 'WebsiteController@chatbotFollowUpConfirm')
    ->middleware('throttle:30,1')->name('chatbot.followup.confirm');




Route::get('/investor-education', 'WebsiteController@investorEducation')->name('investorEducation');
Route::get('/webinars', 'WebsiteController@webinars')->name('webinars');
Route::get('/tools', 'WebsiteController@tools')->name('tools');





Route::get('/admin', 'Admin\AdminController@adminLogin')->middleware('guest');

Auth::routes();
Route::get('/logout', 'HomeController@logout')->name('logout');


//Client Routes
Route::group(['prefix' => 'user','middleware'=>['client','auth']], function () {

    Route::get('/checkout', 'WebsiteController@checkoutPage')->name('checkoutPage');
    Route::get('/seminar/{id}/checkout', 'WebsiteController@seminarCheckoutPage')->name('seminarCheckoutPage');



    Route::get('/course/{id}', 'WebsiteController@educationDetails')->name('educationDetails');

    Route::get('/dashboard', 'Client\ProfileController@index')->name('client_home');
    Route::get('/dashboard/licenses', 'Client\ProfileController@licenses')->name('client_licenses');
    Route::post('/dashboard/licenses', 'Client\ProfileController@licenseSave')->name('client_licenseSave');
    Route::get('/dashboard/license/{id}/delete', 'Client\ProfileController@licenseDelete')->name('deleteLicense');
    Route::get('/paymentHistory', 'Client\ProfileController@paymentHistory')->name('paymentHistory');



    Route::get('/', 'Client\ProfileController@index');

    // Profile
    Route::get('/profile','Client\ProfileController@index')->name('client_profile');
    Route::post('/update-profile','Client\ProfileController@updateProfile')->name('updateProfile');
    Route::post('/save-clinic','Client\ProfileController@SaveClinic')->name('saveClinic');
    Route::post('/profile/picture','Client\ProfileController@updateProfileImg')->name('updateProfileImg');


    Route::get('/profile/settings', 'Client\ProfileController@settings')->name('client_settings');
    Route::put('/profile/settings/{id}', 'Client\ProfileController@updatePassword');

    Route::post('/enroll', 'EducationController@cartEnroll')->name('cartEnroll');
    Route::post('/enroll/seminar-cart', 'EducationController@seminarCartEnroll')->name('seminarCartEnroll');
    Route::post('/enroll/unified-cart', 'EducationController@unifiedCartEnroll')->name('unifiedCartEnroll');
    Route::get('/courses/{id}/enroll', 'EducationController@courseEnroll')->name('client_CourseEnroll');
    Route::post('/seminar/{id}/enroll', 'EducationController@seminarEnroll')->name('client_seminarEnroll');

    Route::get('/my-courses', 'EducationController@myCourses')->name('client_Courses');
    Route::get('/my-course/{id}/{year}', 'EducationController@myCourse')->name('client_Course');
    Route::get('/my-course/{id}/chapter/{chapterId}/{year}', 'EducationController@myCourseChapter')->name('myCourseChapter');
    Route::get('/my-course/{id}/chapter/{chapterId}/learning/{year}', 'EducationController@topics')->name('myCourseChapterLearning');

    Route::get('/my-course/{id}/chapter/{chapterId}/{year}/quiz', 'QuizController@quiz')->name('chapterQuiz');
    Route::get('/my-course/{id}/chapter/{chapterId}/{year}/take-quiz', 'QuizController@TakeQuiz')->name('chapterTakeQuiz');
    Route::get('/my-course/{id}/chapter/{chapterId}/{year}/complete', 'EducationController@chapterComplete')->name('chapterComplete');
    Route::get('/my-courses/{id}/{year?}/evaluation', 'EducationController@courseEvaluation')->name('courseEvaluation');
    Route::post('/my-course/save-evaluation', 'EducationController@saveEvaluation')->name('saveEval');
    Route::get('/my-course/{id}/generate-certificate/{send_mail?}/{year?}', 'EducationController@generateCertificate')->name('generateCertificate');


    Route::post('/my-course/addTime', 'EducationController@addTime')->name('addTime');
    Route::post('/my-course/saveUserExercise', 'EducationController@saveUserExercise')->name('saveUserExercise');




    Route::get('/live-events', 'EducationController@myEvents')->name('client_events');
    Route::get('/live-event/{id}', 'EducationController@myEvent')->name('client_event');
    Route::get('/live-event/{id}/certificate', 'EducationController@generateSeminarCertificate')->name('seminarCertificate');


    Route::get('/quiz','QuizController@UserQuizzes')->name('client_quiz');
    Route::get('/take-quiz/{id}','QuizController@UserTakeQuiz')->name('client_TakeQuiz');
    Route::post('/save-quiz','QuizController@UserSaveQuiz');
    Route::get('/quiz/{id}/get-certificate','QuizController@getCertificate')->name('client_getCertificate');

    Route::get('/certificates', 'QuizController@certificates')->name('client_certificates');

    Route::post('/upload/user-files','EducationController@UploadUserFiles')->name('uploadUserFiles');


});


//Admin Routes
Route::group(['prefix' => 'admin','middleware'=>['verified','admin','auth']], function () {
    Route::get('/mail-test/evaluation', 'Admin\EvaluationMailTestController@send')
        ->middleware('throttle:1,1')->name('admin_mail_test_evaluation'); //JUST FOR TESTING // EVALUATION TEST // https://www.professionalonlineeducation.com/admin/mail-test/evaluation
    // CERTIFCATE GENERATE TEST LINK: https://www.professionalonlineeducation.com/dev/sendCompletion/35/47/2026
    // Analytics
    Route::get('/analytics', 'Admin\AdminController@analytics')->name('admin_analytics');

    // Zoom API Routes
    Route::get('/zoom',                              'Admin\ZoomController@index')->name('admin_zoom');
    Route::get('/zoom/test',                         'Admin\ZoomController@testConnection')->name('admin_zoom_test');
    Route::post('/zoom/create-meeting',              'Admin\ZoomController@createMeeting')->name('admin_zoom_create');
    Route::delete('/zoom/delete-meeting/{lessonId}', 'Admin\ZoomController@deleteMeeting')->name('admin_zoom_delete');
    Route::post('/zoom/update-meeting/{lessonId}',   'Admin\ZoomController@updateMeeting')->name('admin_zoom_update');
    Route::get('/zoom/status/{meetingId}',           'Admin\ZoomController@getMeetingStatus')->name('admin_zoom_status');
    // Profile
    Route::get('/dashboard', 'Admin\AdminController@dashboard')->name('admin_dashboard');
    Route::get('/profile', 'Admin\AdminController@profile')->name('admin_profile');
    Route::post('/profile/update', 'Admin\AdminController@UpdateProfile');
    // Customers
    Route::get('/clients', 'Admin\AdminController@clients')->name('admin_client_view');
    Route::get('/clients/add', 'Admin\AdminController@createClientForm')->name('admin_create_client');
    Route::post('/clients/add', 'Admin\AdminController@storeClientWithCourse')->name('admin_store_client');
    Route::get('/orphan-payments', 'Admin\AdminController@orphanPayments')->name('admin_orphan_payments');
    Route::post('/orphan-payments/reconcile', 'Admin\AdminController@reconcileAllOrphans')->name('admin_reconcile_orphans');
    Route::get('/client/{id}', 'Admin\AdminController@clientDetails')->name('admin_client');
    Route::get('/client/{id}/courses', 'Admin\AdminController@clientCourses')->name('admin_clientCourses');
    Route::post('/client/{id}/grant-course', 'Admin\AdminController@grantClientCourse')->name('admin_grant_client_course');
    Route::post('/client/{userId}/payment-log/{logId}/enroll', 'Admin\AdminController@enrollFromPayment')->name('admin_enroll_from_payment');
    Route::post('/client/{userId}/course/{lessonUserId}/resend-completion', 'Admin\AdminController@resendCourseCompletion')->name('admin_resendCourseCompletion');
    Route::get('/client/{id}/payment-logs', 'Admin\AdminController@clientPaymentLogs')->name('admin_clientPaymentLogs');
    Route::get('/client/{id}/licences', 'Admin\AdminController@clientLicences')->name('admin_clientLicences');
    Route::get('/client/{id}/details', 'Admin\AdminController@clientDetailsView')->name('admin_clientDetails');
    Route::get('/client/{id}/activity', 'Admin\AdminController@clientActivity')->name('admin_clientActivity');
    Route::post('/client/{id}/chapter-time', 'Admin\AdminController@updateClientChapterTime')->name('admin_update_chapter_time');
    Route::get('/client/{id}/delete', 'Admin\AdminController@clientDelete')->name('admin_deleteUser');
    Route::get('/client/{id}/deactivate', 'Admin\AdminController@clientDeactivate')->name('admin_clientDeactivate');
    Route::get('/client/{id}/activate', 'Admin\AdminController@clientActivate')->name('admin_clientActivate');
    Route::get('/client/delete-course/{lesson_id}/{user_id}', 'Admin\AdminController@deleteClientCourse')->name('admin_delete_client_course');
    Route::get('/client/toggle-course/{lesson_id}/{user_id}/{is_show}', 'Admin\AdminController@toggleClientCourse')->name('admin_toggle_client_course');


    Route::post('/password/update', 'Admin\AdminController@UpdateUserPassword')->name('admin_user_pwd_update');
    Route::post('/user/update', 'Admin\AdminController@UpdateUser')->name('admin_user_update');


//    Lessons Cat
    Route::get('/lessons', 'EducationController@lessonsLists')->name('admin_lessons');
    Route::get('/lessons/add', 'EducationController@lessonsAdd')->name('admin_add_lessons');
    Route::get('/lessons/{id}/edit', 'EducationController@lessonsEdit')->name('admin_edit_lessons');
    Route::get('/lessons/{id}/delete', 'EducationController@lessonsDelete')->name('admin_delete_lessons');
    Route::post('/lessons/save', 'EducationController@lessonsSave');

    Route::get('/lessons/{id}/users', 'Admin\AdminController@lessonUsers')->name('adminLessonUsers');

    Route::get('/view-chapters/{id}', 'EducationController@viewChap')->name('admin_viewChap');
    Route::get('/chapters/add/{id}', 'EducationController@ChapAdd')->name('admin_add_Chap');
    Route::get('/chapters/edit/{id}', 'EducationController@ChapEdit')->name('admin_edit_Chap');
    Route::post('/chapter/save', 'EducationController@chapSave')->name('admin_save_chap');
    Route::post('/chapter/update', 'EducationController@chapUpdate');
    Route::get('/chapter/{id}/delete', 'EducationController@chapterDelete')->name('admin_delete_chapter');
   // Route::get('/download/{file}', 'Client\ProjectController@download');
    Route::get('/download/', 'EducationController@downloadPdf')->name('downloadPdf');

    Route::post('/topic/delete', 'EducationController@deleteTopic')->name('deleteTopic');

    Route::post('/chapter/topic/save', 'EducationController@topicSave')->name('saveTopic');

    Route::get('/exercises', 'QuizController@viewExercises')->name('admin_exercises');
    Route::get('/exercise/add', 'QuizController@exerciseAdd')->name('admin_add_exercise');
    Route::post('/exercise/save', 'QuizController@exerciseSave');
    Route::get('/exercise/edit/{id}', 'QuizController@exerciseEdit')->name('admin_edit_exercise');
    Route::get('/exercise/{id}/add-question', 'QuizController@addQuestionExercise')->name('admin_addQuestion_exercise');
    Route::get('/exercise/{id}/edit-question/{qid}', 'QuizController@editQuestionExercise')->name('admin_editQuestion_exercise');
    Route::get('/exercise/{id}/questions', 'QuizController@questionsExercise')->name('admin_questions_exercise');
    Route::post('/exercise/question/save', 'QuizController@exerciseQuestionSave');


    Route::get('/quizzes', 'QuizController@viewQuizzes')->name('admin_quizzes');
    Route::get('/quiz/add', 'QuizController@quizAdd')->name('admin_add_quiz');
    Route::post('/quiz/save', 'QuizController@quizSave');
    Route::get('/quiz/{id}/delete', 'QuizController@quizDelete')->name('admin_delete_quiz');
    Route::get('/quiz/edit/{id}', 'QuizController@quizEdit')->name('admin_edit_quiz');

    Route::get('/quiz/{id}/add-question', 'QuizController@addQuestion')->name('admin_addQuestion');
    Route::get('/quiz/{id}/edit-question/{qid}', 'QuizController@editQuestion')->name('admin_editQuestion');
    Route::get('/quiz/{id}/questions', 'QuizController@questions')->name('admin_questions');

    Route::post('/quiz/question/save', 'QuizController@questionSave');
    Route::get('/quiz/{id}/delete-question/{qid}', 'QuizController@questionDelete')->name('admin_DeleteQuestion');

    Route::get('/evaluation', 'EvaluationController@list')->name('admin_evaluation');
    Route::get('/evaluation/add', 'EvaluationController@addEvaluation')->name('admin_add_evaluation');
    Route::get('/evaluation/{id}/edit', 'EvaluationController@editEvaluation')->name('admin_edit_evaluation');
    Route::get('/evaluation/add-question', 'EvaluationController@addQuestion')->name('admin_evaluation_addQuestion');
    Route::post('/evaluation/save', 'EvaluationController@save');

    Route::get('/approvals', 'EducationController@approvals')->name('admin_approvals');
    Route::get('/approval/add', 'EducationController@approvalAdd')->name('admin_approvalAdd');
    Route::get('/approval/{id}/edit', 'EducationController@approvalEdit')->name('admin_approvalEdit');
    Route::get('/approval/{id}/delete', 'EducationController@approvalDelete')->name('admin_delete_Approval');
    Route::post('/approval/save', 'EducationController@approvalSave');

    Route::get('/approval/courses', 'EducationController@approvalCourses')->name('admin_courseApprovals');
    Route::get('/approval/course', 'EducationController@approvalCourseAdd')->name('admin_approvalCourseAdd');
    Route::get('/approval/course/{id}/edit', 'EducationController@approvalCourseEdit')->name('admin_approvalCourseEdit');
    Route::post('/approval/course/save', 'EducationController@approvalCourseSave');

    Route::get('/approval/seminars', 'EducationController@approvalSeminars')->name('admin_SeminarApprovals');
    Route::get('/approval/seminar', 'EducationController@approvalSeminarAdd')->name('admin_approvalSeminarAdd');
    Route::get('/approval/seminar/{id}/edit', 'EducationController@approvalSeminarEdit')->name('admin_approvalSeminarEdit');
    Route::post('/approval/seminar/save', 'EducationController@approvalSeminarSave');




  Route::resource('bot-responses', 'Admin\BotResponseController');
    Route::resource('chatbot-qas', 'Admin\ChatbotQaController')->except(['show'])->names([
        'index' => 'admin.chatbot-qas.index',
        'create' => 'admin.chatbot-qas.create',
        'store' => 'admin.chatbot-qas.store',
        'edit' => 'admin.chatbot-qas.edit',
        'update' => 'admin.chatbot-qas.update',
        'destroy' => 'admin.chatbot-qas.destroy',
    ]);
    Route::resource('seminars', 'Admin\SeminarController');
    Route::post('/seminars/{seminarId}/attendees/{seminarUserId}/mark-attended', 'Admin\SeminarController@markAttended')->name('seminar.markAttended');
    Route::post('/seminars/{seminarId}/attendees/{seminarUserId}/unmark-attended', 'Admin\SeminarController@unmarkAttended')->name('seminar.unmarkAttended');
    Route::resource('regulations', 'Admin\RegulationController');




    Route::resource('projects', 'Admin\ProjectController');

        
    //coupon routes
    Route::get('/coupons', 'Admin\CouponController@index')->name('admin.coupons.index');
    Route::post('/coupons/store', 'Admin\CouponController@store')->name('admin.coupons.store');
    Route::get('/coupons/edit/{id}', 'Admin\CouponController@edit')->name('admin.coupons.edit');
    Route::get('/coupons/delete/{id}', 'Admin\CouponController@delete')->name('admin.coupons.delete');
    Route::get('/coupons/status/{id}/{status}', 'Admin\CouponController@updateStatus')->name('admin.coupons.update_status');
        //user bot conversation routes
    Route::get('/user-bot-conversation', 'Admin\UserBotConversationController@index')->name('admin.user_bot_conv.index');
    Route::get('/user-bot-conversation/{ip}/{date}', 'Admin\UserBotConversationController@search')->name('admin.user_bot_conv.search');

});


Route::post('/review/dismiss', function(){ session(['review_dismissed'=>true]); return response()->json(['ok'=>true]); })->name('review.dismiss')->middleware('auth');

Route::get('/dev/{userId}/{courseId}','EducationController@sendEvalDev');
Route::get('/dev/sendCompletion/{userId}/{courseId}/{year?}','EducationController@sendCourseCompletion');
Route::get('/dev/sendSeminarEmail/{userId}/{seminarId}','EducationController@sendNewSeminar');
Route::get('/dev/orderEmail','EducationController@devCartEmail');


Route::get('/dev/role',"DevController@createRole");
Route::get('/dev',"EducationController@devCartEnroll");
Route::get('/admin-assets')->name('admin.assets');
Route::get('/download/', 'EducationController@download')->name('downloadPdf');

Route::get('dev/my-course/{id}/chapter/{chapterId}/learning', 'EducationController@topicsDev');

//Route::get('/login', 'Auth\LoginController@LoginRegister');
//Route::get('/register', 'Auth\LoginController@LoginRegister');

Route::get('/clear-cache', function () {
    // Only allow in local or for authorized users (important!)
    \Artisan::call('cache:clear');
});
