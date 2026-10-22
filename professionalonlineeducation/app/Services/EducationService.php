<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\CourseApprovalCategory;
use App\Models\CourseApprovalState;
use App\Models\Evaluation;
use App\Models\Lesson;
use App\Models\LessonChapterUser;
use App\Models\LessonUser;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Seminar;
use App\Models\SeminarApproval;
use App\Models\SeminarApprovalCategories;
use App\Models\SeminarApprovalState;
use App\Models\SeminarUser;
use App\Models\Topic;
use App\Models\UserEvaluation;
use App\Models\UserFiles;
use App\Models\UserQuiz;
use App\Models\UserQuizAnswer;
use App\Notifications\CourseCompletionAdminNotification;
use App\Notifications\CourseCompletionPaperUserNotification;
use App\User;
use App\Notifications\CourseCompletionUserNotification;
use App\Notifications\EvaluationNotification;
use App\Notifications\UserUploadAdminNotification;
use Auth;
use Carbon\Carbon;
use F9Web\LaravelDeletable\Exceptions\NoneDeletableModel;
use Illuminate\Support\Facades\App;
use Notification;
use App\Models\Lesson as model;
use App\Models\Chapter;
use App\Models\CourseCategory;
use App\Models\CourseState;
use App\Models\CourseApproval;
use App\Support\StudentEnrollmentLoader;
use App\Http\Helpers\General\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EducationService
{

    public function LessonList($search=false){
        try{
            $query = model::query()
                ->select('id', 'title', 'title_two', 'title_dev', 'image', 'evaluation_id', 'sort_by', 'status')
                ->orderBy('sort_by', 'asc');

            if ($search){
                $query->where('title', 'like', '%'.$search.'%');
            }

            $list = $query->get();
            $this->attachEvaluationTitles($list);

            return ['type' => 'success', 'data' => $list];
        } catch (\Throwable $exception){
            Helper::errorHandling($exception);
            return ['type' => 'error', 'data' => collect(), 'msg' => $exception->getMessage()];
        }
    }

    /**
     * Admin course list — uses direct DB reads so missing Eloquent columns/scopes
     * on older live databases do not return an empty table.
     */
    public function adminLessonList()
    {
        $meta = [
            'database' => '',
            'raw_count' => 0,
        ];

        try {
            if (! Schema::hasTable('lessons')) {
                return ['type' => 'error', 'data' => collect(), 'msg' => 'lessons table not found', 'meta' => $meta];
            }

            $meta['database'] = DB::connection()->getDatabaseName();
            $meta['raw_count'] = (int) DB::table('lessons')->count();

            $columns = ['id', 'title', 'title_two', 'title_dev', 'image', 'evaluation_id', 'status'];
            foreach (['sort_by', 'deleted_at'] as $optional) {
                if (Schema::hasColumn('lessons', $optional)) {
                    $columns[] = $optional;
                }
            }

            $query = DB::table('lessons')->select($columns);

            if (Schema::hasColumn('lessons', 'sort_by')) {
                $query->orderBy('sort_by', 'asc');
            } else {
                $query->orderBy('id', 'asc');
            }

            $lessons = collect($query->get());

            // Fallback: if selective query returned nothing but rows exist, load all columns.
            if ($lessons->isEmpty() && $meta['raw_count'] > 0) {
                \Log::warning('adminLessonList: selective query empty but raw count > 0 — using full table read');
                $lessons = collect(DB::table('lessons')->orderBy('id', 'asc')->get());
            }

            $this->attachEvaluationTitles($lessons);

            \Log::info('adminLessonList', ['count' => $lessons->count(), 'meta' => $meta]);

            return ['type' => 'success', 'data' => $lessons, 'meta' => $meta];
        } catch (\Throwable $exception) {
            Helper::errorHandling($exception);

            try {
                $meta['database'] = DB::connection()->getDatabaseName();
                $meta['raw_count'] = (int) DB::table('lessons')->count();
                if ($meta['raw_count'] > 0) {
                    $lessons = collect(DB::table('lessons')->orderBy('id', 'asc')->get());
                    $this->attachEvaluationTitles($lessons);

                    return ['type' => 'success', 'data' => $lessons, 'meta' => $meta];
                }
            } catch (\Throwable $inner) {
                Helper::errorHandling($inner);
            }

            return ['type' => 'error', 'data' => collect(), 'msg' => $exception->getMessage(), 'meta' => $meta];
        }
    }

    /**
     * @param  \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection  $lessons
     */
    protected function attachEvaluationTitles($lessons)
    {
        if ($lessons->isEmpty() || ! Schema::hasTable('evaluations')) {
            return;
        }

        $evalIds = $lessons->pluck('evaluation_id')->filter()->unique()->values();
        if ($evalIds->isEmpty()) {
            return;
        }

        $titles = DB::table('evaluations')->whereIn('id', $evalIds)->pluck('title', 'id');

        foreach ($lessons as $lesson) {
            $evaluationId = $lesson->evaluation_id ?? null;
            if ($evaluationId && isset($titles[$evaluationId])) {
                $lesson->evaluation = (object) [
                    'id' => $evaluationId,
                    'title' => $titles[$evaluationId],
                ];
            } else {
                $lesson->evaluation = null;
            }
        }
    }

    public function recentLesson($limit,$except=false){
        $model = new model();
        $posts = $model::select('*');
        if ($except){
            $posts->where('id','!=',$except);
        }
        $posts = $posts->orderBy('created_at','desc')->limit($limit)->get();

        return $posts;
    }
    public function LessonSave($request){

        try{

            $model = new model();
            $exceptRequest = array(
                '_method','_token','id','thumbnail_image','category_ids','state_ids','approval_ids','video_intro','pdf'
            );
            if ($request->thumbnail_image){
            $request = $this->manageAttachments($request,'lesson','thumbnail_image','image');
            }
            if ($request->video_intro){
                $request = $this->manageAttachments($request,'lesson','video_intro','video');
            }
            if ($request->pdf){
                $request = $this->manageAttachments($request,'lesson','pdf','pdf_file');
            }
            if (isset($request->id)){
                $model::where('id',$request->id)->update($request->except($exceptRequest));
                $cat = $model::where('id',$request->id)->first();
            }else{
                $cat =  $model::create($request->except($exceptRequest));
            }
//            if (isset($request->approval_ids)){
//                $this->manageApprovals($cat,$request->approval_ids);
//            }
            if (isset($request->category_ids)){
                $this->manageCategories($cat,$request->category_ids);
            }
            if (isset($request->state_ids)){
                $this->manageStates($cat,$request->state_ids);
            }
            return array('type'=>'success','msg' => 'Course successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');

        }
    }

    public function manageCategories($course,$categories){
        $model = new CourseCategory();

        $model::where('course_id',$course->id)->delete();

        foreach ($categories as $category){
            $model::create(['course_id'=>$course->id,'category_id'=>$category]);
        }
        return true;
    }
    public function manageStates($course,$states){
        $model = new CourseState();

        $model::where('course_id',$course->id)->delete();

        foreach ($states as $state){
            $model::create(['course_id'=>$course->id,'state_id'=>$state]);
        }
        return true;
    }

    public function getLesson($id){
        try{
            $model = new model();
            $cat = $model::find($id);
            if ($cat){
                return array('type'=>'success','data' => $cat);
            }else{
              abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

    public function LessonDelete($id){
        try{
            $model = new model();
            $webinar = $model::find($id);
            if (file_exists($webinar->thumbnail)) {
                unlink($webinar->thumbnail);
            }
            if ($webinar){
                try {
                    $webinar->delete();
                }catch (NoneDeletableModel  $e){
                    return array('type'=>'error','data' => array(),'msg'=>$e->getMessage());
                }
                return array('type'=>'success','msg' => 'Course successfully deleted');
            }else{
              abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>$exception);

        }
    }

    public function manageAttachments($request,$folder,$fileName,$requestName){

        if ($file = $request->file($fileName)) {

            $memeType = $file->getMimeType();
            $path = "uploads/".$folder;
            if(!is_dir($path)){
                if (!mkdir($path, 0777, true) && !is_dir($path)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
                }
            }
            $name = rand(0,9999).$file->getClientOriginalName();
            $file->move($path, $name);
            $request->request->add([$requestName => $path.'/'.$name]);

        }
        return $request;
    }

    public function chapterListByLessonId($lessonId){
        try{
            $LessonModel = new Lesson();
            $model = new Chapter();
            $lesson = $LessonModel::find($lessonId);
            $chapters = $lesson->chapters;
            $data = array(
                'lesson'=>$lesson,
                'chapters'=>$chapters
            );

            return array('type'=>'success','data' => $data);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }




    }

    public function getChapters($lessonId,$type){
        try{
            $model = new Chapter();
            $chap = $model::where('lesson_id',$lessonId);
           if ($type == 'main'){
               $chap->where('parent_id','0');
           }else{
               $chap->where('parent_id','!=','0');
           }
            $chap = $chap->get();
            return $chap;


        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

    public function getChapterById($id){
        try{
            $model = new Chapter();
            $chap = $model::find($id);
            if ($chap){
                return $chap;
            }else{
                return abort('404');exit();
            }
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

    public function validateChapterNo($lessonId,$chapterNo,$chapterType){
        $model = new Chapter;
        $getChap = $model::where('lesson_id',$lessonId)->where('chapter_no',$chapterNo)->get();
//        dd($chapterNo);
       if (count($getChap)){
           return false;
       }else{
           return true;
       }

    }

    public function validateChapterNoForEdit($lessonId,$chapterNo,$chapterType,$chapId){
        $model = new Chapter;
        $currentChap = $model::find($chapId);
        if($currentChap->chapter_no == $chapterNo){
            return true;
        }
        $getChap = $model::where('lesson_id',$lessonId)->where('chapter_no',$chapterNo)->get();
//        dd($chapterNo);
       if (count($getChap)){
           return false;
       }else{
           return true;
       }

    }
    public function iniChapter($id){
        $model = new Chapter;
        $lastChapNo = $model::where('lesson_id',$id)->orderBy('chapter_no','desc')->first();
        if ($lastChapNo){
        $newChapNo = $lastChapNo->chapter_no+1;
        }else{
            $newChapNo = 1;
        }
        return $model::create(['lesson_id'=>$id,'chapter_no'=>$newChapNo]);
    }
    public function chapSave($request){
      //  dd($request->all());
        try{


            $model = new Chapter;
            if ($request->chapType == 'main'){
                $validate = $this->validateChapterNo($request->lesson_id,$request->chapter_no,$request->chapType);
                if (!$validate){
                    $chapter = $request;
                    return array('type'=>'error','data'=>$chapter,'msg' => 'Cannot add same chapter number twice.');
                }
                $data = array(
                    'lesson_id'=>$request->lesson_id,
                    'chapter_no'=>$request->chapter_no,
                    'title'=>$request->title,
                    'description'=>$request->description,
//                    'exercise_id'=>$request->exercise_id,
                );

            }else{
                $updateMain = $model::find($request->parent_id);
                $validate = $this->validateChapterNo($request->lesson_id,$updateMain->chapter_no.'.'.$request->chapter_no,$request->chapType);
                if (!$validate){
                    $chapter = $request;
                    return array('type'=>'error','data'=>$chapter,'msg' => 'Cannot add same chapter number twice.');
                }

                $data = array(
                    'lesson_id'=>$request->lesson_id,
                    'chapter_no'=>$updateMain->chapter_no.'.'.$request->chapter_no,
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'parent_id'=>$request->parent_id,
//                    'exercise_id'=>$request->exercise_id,
                );


                $updateMain->have_child = '1';
                $updateMain->save();


            }


            $save = $model::create($data);
            $this->removeUnSavedChapters($request->lesson_id);
            return array('type'=>'success','msg' => 'Chapter successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');
        }
    }

    public function removeUnSavedChapters($lesson_id){
        $lesson = Lesson::find($lesson_id);
        if($lesson){
            foreach ($lesson->chapters as $chapter){
                if ($chapter->saved == 0){
                    $chapter->delete();
                }
            }
        }

    }

    public function chapUpdate($request){
// dd($request->all());
        try{
        $model = new Chapter;
        $updateMain = $model::find($request->parent_id);

            if ($request->chapType == 'main'){
                $validate = $this->validateChapterNoForEdit($request->lesson_id,$request->chapter_no,$request->chapType,$request->id);
                if (!$validate){
                    $chapter = $request;
                    return array('type'=>'error','data'=>$chapter,'msg' => 'Cannot add same chapter number twice.');
                }
                $data = array(
                    'lesson_id'=>$request->lesson_id,
                    'chapter_no'=>$request->chapter_no,
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'parent_id'=>'0',
                    'quiz_id'=>$request->quiz_id,
                    'saved'=>'1',
                );

            }else{

                // Guard: sub-chapter requires a valid parent
                if (!$updateMain) {
                    return array('type'=>'error','msg' => 'Parent chapter not found. Please select a valid parent chapter.');
                }

                $validate = $this->validateChapterNoForEdit($request->lesson_id,$updateMain->chapter_no.'.'.$request->chapter_no,$request->chapType,$request->id);
                if (!$validate){
                    $chapter = $request;
                    return array('type'=>'error','data'=>$chapter,'msg' => 'Cannot add same chapter number twice.');
                }

                $data = array(
                    'lesson_id'=>$request->lesson_id,
                    'chapter_no'=>$updateMain->chapter_no.'.'.$request->chapter_no,
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'parent_id'=>$request->parent_id,
                    'quiz_id'=>$request->quiz_id,
                    'saved'=>'1',
//                    'exercise_id'=>$request->exercise_id,
                );

                $updateMain->have_child = '1';

            }

            // Only save parent if it exists (main chapters have no parent)
            if ($updateMain) {
                $updateMain->save();
            }
            $save = $model::where('id',$request->id)->update($data);
            $this->removeUnSavedChapters($request->lesson_id);
            return array('type'=>'success','msg' => 'Chapter successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later','data'=>$request);

        }
    }

    public function chapTitleSave($request){
        //dd($request->all());
        $modal = new Chapter();
        $chapter = $modal::find($request->ChapterId);
        $chapter->title = $request->title;
        $chapter->exercise_id = $request->exercise_id;
        $chapter->quiz_id = $request->quiz_id;
        $chapter->description = $request->description;
        $chapter->saved = 1;
        $chapter->save();
        return true;
    }

    public function chapterDelete($id){
        try{
            $model = new Chapter();
            $chapter= $model::find($id);
            if ($chapter){
                if ($chapter->parent_id == '0'){
                   $subChapters = $model::where('parent_id',$chapter->id)->get();
                   if (count($subChapters)){
                       foreach ($subChapters as $subChapter){
                           $model::where('id',$subChapter->id)->delete();
                       }
                   }
                    $chapter->delete();
                }else{
                    $chapter->delete();
                }

                return array('type'=>'success','msg' => 'Chapter successfully deleted','lesson_id'=>$chapter->lesson_id);
            }else{
                abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

    public function topicSave($request){
        // dd($request->all());
        try{

            $model = new Topic;
            $data = [
                'chapter_id'=>$request->chapter_id,
                'title'=>$request->title,
                'description'=>$request->description,
                'lesson_id'=>$request->course_id,
                'exercise_id'=>$request->exercise_id,
            ];
            if ($request->topic_id == null){
                $save = $model::create($data);
            }else{
                $save = $model::where('id',$request->topic_id)->update($data);
            }
            return array('type'=>'success','msg' => 'Topic successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');
        }
    }
    public function deleteTopic($request){
        $topic = $request->id;
        $model = new Topic();
        $model::where('id',$topic)->delete();
    }



    // Website

    public function coursePage($id){
        $model = new model();
        $data = array();
        $lesson = $model::find($id);
        if (!$lesson){
            return array('type'=>'error','data' => array());
        }
        $data['lesson'] = $lesson;
        $data['chapters'] = $lesson->chapters;
        $data['mainChapters'] = $lesson->msinChapters;
        $data['type'] = 'success';

        return $data;


    }

    public function getRandomLesson($limit){
        $model = new model();
        $posts = $model::inRandomOrder()->limit($limit)->get();
        return $posts;
    }
    public function getRandomChapter($limit){
        $model = new Chapter();
        $posts = $model::inRandomOrder()->limit($limit)->get();
        return $posts;
    }
    public function approvals(){
        $model = new Approval();
        return $model::all();
    }
    public function approvalSave($request){
        try{
            $model = new Approval();
            $data = [
                'approval'=>$request->title,
            ];
            if ($request->id == null){
                $save = $model::create($data);
            }else{
                $save = $model::where('id',$request->id)->update($data);
            }
            return array('type'=>'success','msg' => 'Approval successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');
        }
    }

    public function deleteApprovals($id){
        $model = new Approval();
        $approval = $model::find($id);
        if (!$approval){ return abort('404'); exit(); }
        $approval->delete();
        return array('type'=>'success','msg' => 'Approval successfully deleted');
    }

    public function courseApprovalsList(){
        $model = new CourseApproval();
        return $model::all();
    }
    public function SeminarApprovalsList(){
        $model = new SeminarApproval();
        return $model::all();
    }
    public function courseApprovalById($id){
        $model = new CourseApproval();
        $courseApproval = $model::find($id);
        if (!$courseApproval){ return abort('404'); exit(); }
        return $courseApproval;
    }
    public function seminarApprovalById($id){
        $model = new SeminarApproval();
        $seminarApproval = $model::find($id);
        if (!$seminarApproval){ return abort('404'); exit(); }
        return $seminarApproval;
    }

    public function approvalCourseSave($request){
        try{
            $model = new CourseApproval();
            $data = [
                'lesson_id'=>$request->course_id,
                'approval_id'=>$request->approval_id,
                'approval_no'=>$request->approval_no,
                'provider_no'=>$request->provider_no,
                'start_date'=>$request->start_date,
            ];
            if(isset($request->end_date)){
                $data['end_date'] = $request->end_date;
            }
//            dd($data);
            if ($request->id == null){
                $courseApproval = $model::create($data);
            }else{
                $model::where('id',$request->id)->update($data);
                $courseApproval = $model::find($request->id);
            }
            if ($request->state_ids){
                $this->manageApprovalStates($courseApproval,$request->state_ids);
            }
            if ($request->category_ids){
                $this->manageApprovalCategories($courseApproval,$request->category_ids);
            }
            return array('type'=>'success','msg' => 'Course Approval successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');
        }
    }

    public function approvalSeminarSave($request){
        try{

            $model = new SeminarApproval();
            $data = [
                'seminar_id'=>$request->seminar_id,
                'approval_id'=>$request->approval_id,
                'approval_no'=>$request->approval_no,
                'provider_no'=>$request->provider_no,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
            ];
            if ($request->id == null){
                $seminarApproval = $model::create($data);
            }else{
                $model::where('id',$request->id)->update($data);
                $seminarApproval = $model::find($request->id);
            }
            if ($request->state_ids){
                $this->manageSeminarApprovalStates($seminarApproval,$request->state_ids);
            }
            if ($request->category_ids){
                $this->manageSeminarApprovalCategories($seminarApproval,$request->category_ids);
            }
            return array('type'=>'success','msg' => 'Seminar Approval successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');
        }
    }


    public function manageApprovalStates($approval,$stateIds){
        $model = new CourseApprovalState();
        $model::where('course_approval_id',$approval->id)->delete();
        foreach ($stateIds as $stateId){
            $model::create(['course_approval_id'=>$approval->id,'state_id'=>$stateId]);
        }
        return true;
    }
    public function manageApprovalCategories($approval,$categoryIds){
        $model = new CourseApprovalCategory();
        $model::where('course_approval_id',$approval->id)->delete();
        foreach ($categoryIds as $categoryId){
            $model::create(['course_approval_id'=>$approval->id,'category_id'=>$categoryId]);
        }
        return true;
    }
    public function manageSeminarApprovalStates($approval,$stateIds){
        $model = new SeminarApprovalState();
        $model::where('seminar_approval_id',$approval->id)->delete();
        foreach ($stateIds as $stateId){
            $model::create(['seminar_approval_id'=>$approval->id,'state_id'=>$stateId]);
        }
        return true;
    }
    public function manageSeminarApprovalCategories($approval,$categoryIds){
        $model = new SeminarApprovalCategories();
        $model::where('seminar_approval_id',$approval->id)->delete();
        foreach ($categoryIds as $categoryId){
            $model::create(['seminar_approval_id'=>$approval->id,'category_id'=>$categoryId]);
        }
        return true;
    }

    public function homePageData(){
        $model = new Lesson();
        $courses = $model::select('*')->limit('6')->inRandomOrder()->get();
        return ['courses'=>$courses];
    }




    function RemoveSpecialChar($str) {

        // Using str_replace() function
        // to replace the word
        $res = str_replace( array( '\'', '"',
            ',' , ';', '<', '>',"$" ), ' ', $str);

        // Returning the result
        return $res;
    }


    // User
    public function seminarEnroll($id){
        try{
            $user = auth()->user();
            $seminar = Seminar::find($id);
                $now = \Carbon\Carbon::now();
                $data = [
                    'user_id'=>$user->id,
                    'seminar_id'=>$seminar->id,
                    'enrolled_at'=>$now,
                    'price'=> $this->RemoveSpecialChar($seminar->cost)
                ];
                SeminarUser::create($data);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');
        }
    }


    public function courseEnroll($id){
        $user = auth()->user();
        if (!$user) {
            return ['type' => 'error', 'msg' => 'You must be logged in to enroll.'];
        }

        return $this->grantCourseToUser((int) $user->id, (int) $id, [
            'price' => optional(Lesson::find($id))->price,
            'respectYearLimit' => true,
        ]);
    }

    /**
     * Enroll a student in a course (checkout, admin grant, or payment reconciliation).
     */
    public function grantCourseToUser(int $userId, int $lessonId, array $options = []): array
    {
        try {
            $course = Lesson::withTrashed()->find($lessonId);
            if (!$course) {
                return ['type' => 'error', 'msg' => 'Course not found.'];
            }
            if (!User::find($userId)) {
                return ['type' => 'error', 'msg' => 'Student not found.'];
            }

            $year = (int) ($options['year'] ?? date('Y'));
            $price = array_key_exists('price', $options) ? $options['price'] : $course->price;
            $respectYearLimit = (bool) ($options['respectYearLimit'] ?? false);

            $existing = LessonUser::where('user_id', $userId)
                ->where('lesson_id', $lessonId)
                ->whereYear('created_at', $year)
                ->first();

            if ($existing) {
                $this->iniChapters($lessonId, $year, $course, $userId);
                return [
                    'type' => 'success',
                    'msg' => 'Student is already enrolled in this course.',
                    'enrollment_id' => $existing->id,
                ];
            }

            if ($respectYearLimit && !$this->verifyCourseForUser($userId, $lessonId)) {
                return ['type' => 'error', 'msg' => 'Already enrolled in this course for the current year.'];
            }

            $enrollment = LessonUser::create([
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'enrolled_at' => Carbon::now(),
                'price' => $price,
                'status' => 1,
                'is_show' => 0,
            ]);

            $this->iniChapters($lessonId, $year, $course, $userId);

            return [
                'type' => 'success',
                'msg' => 'Course added to student profile successfully.',
                'enrollment_id' => $enrollment->id,
            ];
        } catch (\Throwable $e) {
            \Log::error('grantCourseToUser failed', [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'error' => $e->getMessage(),
            ]);

            return ['type' => 'error', 'msg' => 'Could not add course. Please try again or contact support at 1-888-900-3382.'];
        }
    }

    protected function verifyCourseForUser(int $userId, int $lessonId): bool
    {
        return ! LessonUser::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->whereYear('created_at', date('Y'))
            ->exists();
    }
    protected function verifyCourse($course){
        $user = auth()->user();
        $model = new LessonUser();
        $enrollment = $model::where('user_id',$user->id)->where('lesson_id',$course->id)->whereYear('created_at', date('Y'))->get();

        if (!count($enrollment)){
            return true;
        }else{
            return false;
        }
    }

    public function resolveEnrollmentYear($userId, $lessonId, $year = null){
        if ($year !== null && is_numeric($year)) {
            return (int) $year;
        }
        $enrollment = LessonUser::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($enrollment) {
            return (int) $enrollment->created_at->format('Y');
        }
        return (int) date('Y');
    }
    public function MyCourses($limit=false): array
    {
        $user = auth()->user();
        if (! $user) {
            return ['courses' => collect()];
        }

        $courses = StudentEnrollmentLoader::ensureEnrollments($user, $limit);

        return ['courses' => $courses];
    }
    public function MyCourse($id, $year): array
    {   
        $user = auth()->user();
        $verifyCourse = $this->verifyUserCourse($user->id,$id);
        if ($verifyCourse == null){  return array('type'=>'error','msg' => 'Course Not Found'); }
        $course = Lesson::find($id);
        if (!$course){  return array('type'=>'error','msg' => 'Course Not Found'); }
        $this->iniChapters($course->id, $year, $course);
        $userChapters = $this->getUserLessonChapters($user->id,$course->id, $year);
        $totalMins = 0;
        $chapterTimes = [];
        $chapterPercentages = [];
        $isAllChaptersCompleted = true;
        foreach ($userChapters as $userChapter){
            $totalMins += $userChapter->time_spent;
            $chapterTimes[$userChapter->chapter_id] = $userChapter->time_spent;
            $chapterPercentages[$userChapter->chapter_id] = $userChapter->quiz_percentage;
            if ($userChapter->time_spent <= 54){
                $isAllChaptersCompleted = false;
            }
        }
        
        $completedQuizes = $this->getCompletedChapterIds($user->id, $course->id, $year, $userChapters);
        $seconds = $totalMins * 60;
        $isCertificateAllowed = $this->isCertificateAllowed($user->id,$course->id, $year);
        $isTimeCompleted = $this->isTimeCompleted($user->id,$course->id, $year);
        $isAllQuizPassed = $this->isAllQuizPassed($user->id,$course->id, $year);
        $isEvaluationCompleted = $this->isEvaluationCompleted($course->id, $year);
       
        if ($isEvaluationCompleted){ $isTimeCompleted = true; }
        
        return [
            'type'=>'success',
            'msg' => 'Course Found',
            'chapterPercentages'=>$chapterPercentages,
            'isAllQuizPassed'=>$isAllQuizPassed,
            'isTimeCompleted'=>$isTimeCompleted,
            'course'=>$course,
            'seconds'=>$seconds,
            'chapterTimes'=>$chapterTimes,
            'completedQuizes'=>$completedQuizes,
            'isCertificateAllowed'=>$isCertificateAllowed,
            'isEvaluationCompleted'=>$isEvaluationCompleted,
            'is_completed'          => LessonUser::where('user_id', $user->id)->where('lesson_id', $id)->whereYear('created_at', $year)->orderBy('created_at','desc')->first(),
        ];
    }
    public function isEvaluationCompleted($courseId, $year){
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        $userEvaluation = UserEvaluation::where(['lesson_id'=>$courseId,'user_id'=>$user->id])->whereYear('created_at', $year)->get();
        if ($userEvaluation->isEmpty()) {
            $userEvaluation = UserEvaluation::where(['lesson_id'=>$courseId,'user_id'=>$user->id])->get();
        }

        return $userEvaluation->isNotEmpty();
    }
    public function isTimeCompleted($userId,$courseId, $year){
        $course = Lesson::find($courseId);
        if (! $course) {
            return false;
        }
        $chapterRecords = $this->getUserLessonChapters($userId, $courseId, $year);
        $TotalMinSpent = (float) $chapterRecords->sum('time_spent');
        if ($TotalMinSpent <= 0) {
            $TotalMinSpent = (float) LessonChapterUser::where(['user_id'=>$userId,'lesson_id'=>$courseId])->sum('time_spent');
        }
        $hours = (float) ($course->hours ?: 0);
        $totalMinNeeded = $hours * 60;
        if ($totalMinNeeded > 0 && (($TotalMinSpent / $totalMinNeeded) * 100) >= 75) {
            return true;
        }
        $chapterIds = $course->chapters->pluck('id')->filter()->all();
        if (count($chapterIds) === 0) {
            return $TotalMinSpent >= 55;
        }
        foreach ($chapterIds as $chapterId) {
            $record = $chapterRecords->firstWhere('chapter_id', $chapterId);
            if (! $record || (float) $record->time_spent < 55) {
                return false;
            }
        }
        return true;
    }
    public function isAllQuizPassed($userId,$courseId, $year){
        $courseQuizes = $this->getCourseQuizes($courseId);
        if (count($courseQuizes) === 0) {
            return true;
        }
        $userQuizes = UserQuiz::where('user_id', $userId)
            ->where('lesson_id', $courseId)
            ->whereYear('created_at', $year)
            ->pluck('quiz_id')
            ->toArray();
        $userQuizes = array_unique($userQuizes);
        if (count(array_diff($courseQuizes, $userQuizes)) === 0) {
            return true;
        }

        $chapterRecords = $this->getUserLessonChapters($userId, $courseId, $year);
        $passedQuizIds = $userQuizes;
        foreach ($chapterRecords as $chapterRecord) {
            if ((float) $chapterRecord->quiz_percentage < 75) {
                continue;
            }
            $chapter = $chapterRecord->chapter ?? Chapter::find($chapterRecord->chapter_id);
            if ($chapter && $chapter->quiz_id) {
                $passedQuizIds[] = $chapter->quiz_id;
            }
        }

        return count(array_diff($courseQuizes, array_unique($passedQuizIds))) === 0;
    }
    public function isCertificateAllowed($userId,$courseId, $year){
        return $this->isAllQuizPassed($userId, $courseId, $year)
            && $this->isTimeCompleted($userId, $courseId, $year);
    }
    public function getCourseQuizes($courseId){
        $course = Lesson::find($courseId);
        $quizIds = [];
        if (! $course) {
            return $quizIds;
        }
        foreach ($course->chapters as $chapter){
            if ($chapter->quiz_id && $chapter->quiz){
                $quizIds[] = $chapter->quiz->id;
            }
        }
        return array_values(array_unique($quizIds));
    }
    public function getUserLessonChapters($userId,$lessonId, $year){
        $data = [
            'user_id'=>$userId,
            'lesson_id' => $lessonId,
        ];
        $yearRecords = LessonChapterUser::where($data)->whereYear('created_at', $year)->with('chapter')->get();
        // Records for the enrollment year are authoritative, even with zero progress;
        // falling back to an older year would carry last year's passes into a new attempt.
        if ($yearRecords->isNotEmpty()) {
            return $yearRecords;
        }

        $fallback = LessonChapterUser::where($data)
            ->with('chapter')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('chapter_id')
            ->values();

        return $fallback->isNotEmpty() ? $fallback : $yearRecords;
    }
    public function iniChapters($id, $year, $course=false, $userId = null){
        $lessonChapterUser = new LessonChapterUser();
        $userId = $userId ?? (auth()->check() ? auth()->id() : null);
        if (!$userId) {
            return;
        }
        if (!$course){
            $course = Lesson::find($id);
        }
        if (!$course || !count($course->chapters)){
            return;
        }
                foreach ($course->chapters as $chapter){
                    $data = [
                        'user_id'=>$userId,
                        'lesson_id' => $course->id,
                        'chapter_id'=>$chapter->id,
                    ];
                    if (is_int(intval($year)) && !$lessonChapterUser::where($data)->whereYear('created_at', $year)->first()) {
                        // A new enrollment year is a fresh attempt: do not carry over
                        // last year's time spent / quiz score, otherwise a re-enrolled
                        // student appears to have already finished the course.
                        $data['time_spent'] = 0;
                        $data['quiz_percentage'] = null;
                        $data['status'] = 1;
                        $lessonChapterUser::create($data);
                    }
                }

    }
    public function addTime($request){
        $user = auth()->user();
        $data = [
            'user_id'=>$user->id,
            'lesson_id' => $request->lesson_id,
            'chapter_id'=>$request->chapter_id,
        ];
        $year = $this->resolveEnrollmentYear($user->id, $request->lesson_id, $request->year ?? null);

        $lessonChapterUser = LessonChapterUser::where($data)
            ->whereYear('created_at', $year)
            ->first();

        if (!$lessonChapterUser) {
            $lessonChapterUser = LessonChapterUser::where($data)->orderBy('created_at', 'desc')->first();
        }

        if (!$lessonChapterUser) {
            $lessonChapterUser = LessonChapterUser::create(array_merge($data, [
                'time_spent' => 0,
                'status' => 1,
            ]));
        }

        $time = round($request->time / 60, 2);
        if ($time > $lessonChapterUser->time_spent) {
            $lessonChapterUser->time_spent = $time;
            $lessonChapterUser->save();
        }
    }

    public function myCourseChapter($id,$chapterId,$user, $year)
    {
            
        $verifyCourse = $this->verifyUserCourse($user->id,$id);
        if ($verifyCourse == null){  return array('type'=>'error','msg' => 'Course Not Found'); }
        $course = Lesson::find($id);
        if (!$course){  return array('type'=>'error','msg' => 'Course Not Found'); }
        $chapter = Chapter::find($chapterId);
        if (!$chapter){  return array('type'=>'error','msg' => 'Chapter Not Found'); }
        $chapterData = [
            'user_id' => $user->id,
            'lesson_id' => $id,
            'chapter_id' => $chapterId,
        ];
        $chapterUser = LessonChapterUser::where($chapterData)->whereYear('created_at', $year)->first();
        if (!$chapterUser) {
            $chapterUser = LessonChapterUser::where($chapterData)->orderBy('created_at', 'desc')->first();
        }
        $previousTime = $chapterUser ? ($chapterUser->time_spent * 60) : '0';
//        dd($previousTime);
//        $previousTime = explode('.', $previousTime);
        
        return ['type'=>'success','msg' => 'Course Found','course'=>$course,'chapter'=>$chapter,'previousTime'=>$previousTime];
    }
    public function userQuizzes($user, $year, $type='array')
    {
        $quizzes = [];
        if ($type == 'array'){
            $quizzes = UserQuiz::where('user_id',$user->id)
                ->when($year, function($q) use ($year) {
                    $q->whereYear('created_at', $year);
                })
                ->pluck('quiz_id')->toArray();
        }
        return $quizzes;
    }
    public function getQuiz($id,$chapterId){
        $quiz = UserQuiz::where('user_id',auth()->user()->id)->where('lesson_id',$id)->where('chapter_id',$chapterId)->orderBy('id','desc')->limit(1)->get();
        if (count($quiz)){ return $quiz[0]; }
        return  null;
    }
    public function chapterComplete($id,$chapterId){
        try {
            $user = auth()->user();
            $verifyCourse = $this->verifyUserCourse($user->id,$id);
            if ($verifyCourse == null){  return array('type'=>'error','msg' => 'Course Not Found'); }
            // Time is persisted via addTime before this route is hit; do not reset it here.
            return array('type'=>'success','msg' => 'Chapter completed successfully');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');
        }


    }


    public function verifyUserCourse($userId,$courseId){
        $model = new LessonUser();
        return $model::where([
            'user_id'=>$userId,
            'lesson_id'=>$courseId
        ])->orderBy('created_at','desc')->first();

    }
    public function getcourseEvaluation($lessonId,$year){
        $course = Lesson::find($lessonId);
        if (!$course) {
            return ['type' => 'error', 'msg' => 'Course Not Found'];
        }
        $evalQuery = UserEvaluation::where(['user_id'=>auth()->id(), 'evaluation_id'=>$course->evaluation_id, 'lesson_id'=>$lessonId]);
        $hasEval = (clone $evalQuery)->whereYear('created_at', (int) $year)->exists()
            || $evalQuery->exists();
        if(! $hasEval){
            $user = auth()->user();
            $verifyCourse = $this->verifyUserCourse($user->id,$lessonId);
            if ($verifyCourse == null){  return array('type'=>'error','msg' => 'Course Not Found'); }

            $evaluation = Evaluation::find($course->evaluation_id);
            if (!$evaluation){ 
                return array('type'=>'notFound','msg' => 'Evaluation Not Found'); 
            }
            return array('type'=>'success','msg' => '','evaluation'=>$evaluation,'course'=>$course); 
        }
        return array('type'=>'reprinting','msg' => 'Reprinting certificate'); 

    }
    public function sendCouseComplition($id){
        $this->courseCompletation($id);
        return 'send';
    }
    public function saveEvaluation($request){

            $this->courseCompletation($request->course_id, $request->year);
            $user = User::find(auth()->user()->id);
            $data = [
                'user_id'=>auth()->user()->id,
                'evaluation_id'=>$request->evaluation_id,
                'lesson_id'=>$request->course_id,
                'details'=>json_encode($request->except(['evaluation_id','course_id','_token']))
            ];
            $lesson = Lesson::find($request->course_id);
            $userEvaluation =  UserEvaluation::create($data);
            $userLesson = LessonUser::where('user_id',$user->id)->where('lesson_id',$lesson->id)->whereYear('created_at', (int) $request->year)->orderBy('created_at','desc')->first();
            if (! $userLesson) {
                $userLesson = LessonUser::where('user_id',$user->id)->where('lesson_id',$lesson->id)->orderBy('created_at','desc')->first();
            }
            Helper::sendNotificationSafely(Helper::adminUser(), new EvaluationNotification(['request'=>$userEvaluation,'user'=>auth()->user(),'lesson'=>$lesson,'userLesson'=>$userLesson]), 'evaluation submit admin');
             return $userEvaluation;
    }
    public function sendEvalDev($userId,$courseId){
        $user = User::find($userId);
        $userEval = UserEvaluation::where('user_id',$userId)->where('lesson_id',$courseId)->first();
        if (!$userEval){ return  'not found'; }
        
        $lesson = Lesson::find($courseId);
        // Always use the most-recent enrollment row so dates are correct for re-enrollees
        $userLesson = LessonUser::where('user_id',$user->id)->where('lesson_id',$lesson->id)
            ->orderBy('created_at','desc')->first();
        if (!$userLesson){ return 'not found'; }
        Helper::sendNotificationSafely(Helper::adminUser(), new EvaluationNotification(['request'=>$userEval,'user'=>$user,'lesson'=>$lesson,'userLesson'=>$userLesson]), 'evaluation dev admin');
        return redirect()->route('generateCertificate',[$courseId, 'true', $userLesson ? $userLesson->created_at->format('Y') : date('Y')]);

        return 'sent';

    }
    // Only for dev
    // public function sendCourseCompletion($userId,$course,$year=null){
    //     dd($year);
    //     $lesson = Lesson::find($course);
    //     $user = User::find($userId);

    //     $userQuizzes = UserQuiz::where(['user_id'=>$user->id,'lesson_id'=>$lesson->id])->get();
    //     $totalPercent = UserQuiz::where(['user_id'=>$user->id,'lesson_id'=>$lesson->id])->sum('user_percentage');
    //     $totalPercent = ($totalPercent*100)/(count($userQuizzes)*100);
    //     $data = [
    //         'totalPercent'=>$totalPercent,
    //         'lesson'=>$lesson,
    //         'user'=>$user,
    //         'userLesson'=> LessonUser::where('user_id',$user->id)->where('lesson_id',$lesson->id)->first(),
    //         'userLessonChapters' =>LessonChapterUser::where(['user_id'=>$user->id,'lesson_id'=>$lesson->id])->get(),
    //         'userQuizzes' => $userQuizzes
    //     ];
    //     dd($data);
    //     Notification::send(Helper::adminUser(), new CourseCompletionAdminNotification($data));
    // }
    public function sendCourseCompletion($userId, $course, $year = null)
    {
        $lesson = Lesson::find($course);
        $user = User::find($userId);
    
        // User quizzes
        $userQuizzes = UserQuiz::where(['user_id' => $user->id, 'lesson_id' => $lesson->id])
            ->when($year, fn($query) => $query->whereYear('created_at', $year))
            ->get();
    
        // Total percentage
        $totalPercent = UserQuiz::where(['user_id' => $user->id, 'lesson_id' => $lesson->id])
            ->when($year, fn($query) => $query->whereYear('created_at', $year))
            ->sum('user_percentage');
    
        $totalPercent = count($userQuizzes) > 0 ? ($totalPercent * 100) / (count($userQuizzes) * 100) : 0;
    
        // Other related models — always pick the most-recent enrollment;
        // if a year is provided scope to that year (prevents old re-enrollment rows showing wrong dates)
        $userLesson = LessonUser::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->when($year, fn($query) => $query->whereYear('created_at', $year))
            ->orderBy('created_at', 'desc')
            ->first();
    
        $userLessonChapters = LessonChapterUser::where(['user_id' => $user->id, 'lesson_id' => $lesson->id])
            ->when($year, fn($query) => $query->whereYear('created_at', $year))
            ->get();
    
        $data = [
            'totalPercent' => $totalPercent,
            'lesson' => $lesson,
            'user' => $user,
            'userLesson' => $userLesson,
            'userLessonChapters' => $userLessonChapters,
            'userQuizzes' => $userQuizzes
        ];
        Helper::sendNotificationSafely(Helper::adminUser(), new CourseCompletionAdminNotification($data), 'course completion admin');
    }

    public function courseCompletation($lessonId, $year=null){
        $year = $year ?? date('Y');
        $this->markCourseCompleted($lessonId, $year);
        $lesson = Lesson::find($lessonId);
        if (!$lesson) {
            return;
        }
        $user = auth()->user();
        // Scope to current enrollment year so old re-enrollment data is excluded
        $year = $year ?? date('Y');
        $userQuizzes = UserQuiz::where(['user_id'=>$user->id,'lesson_id'=>$lesson->id])
            ->whereYear('created_at', $year)->get();
        if ($userQuizzes->isEmpty()) {
            $userQuizzes = UserQuiz::where(['user_id'=>$user->id,'lesson_id'=>$lesson->id])->get();
        }
        $totalPercent = $userQuizzes->sum('user_percentage');
        if(count($userQuizzes)){
            $totalPercent = ($totalPercent*100)/(count($userQuizzes)*100);
        }else{
            $totalPercent = 100;
        }

        $userLesson = LessonUser::where('user_id',$user->id)->where('lesson_id',$lesson->id)
            ->whereYear('created_at', $year)->orderBy('created_at','desc')->first();
        if (! $userLesson) {
            $userLesson = LessonUser::where('user_id',$user->id)->where('lesson_id',$lesson->id)
                ->orderBy('created_at','desc')->first();
        }
        $userLessonChapters = LessonChapterUser::where(['user_id'=>$user->id,'lesson_id'=>$lesson->id])
            ->whereYear('created_at', $year)->get();
        if ($userLessonChapters->isEmpty()) {
            $userLessonChapters = LessonChapterUser::where(['user_id'=>$user->id,'lesson_id'=>$lesson->id])->get();
        }

        $data = [
            'totalPercent'=>$totalPercent,
            'lesson'=>$lesson,
            'user'=>$user,
            'userLesson'=> $userLesson,
            'userLessonChapters' => $userLessonChapters,
            'userQuizzes' => $userQuizzes
        ];
        Helper::sendNotificationSafely(Helper::adminUser(), new CourseCompletionAdminNotification($data), 'course completion admin');
        Helper::sendNotificationSafely($user, new CourseCompletionPaperUserNotification($data), 'course completion user');
//        Notification::send($user, new CourseCompletionUserNotification($data));


    }


    public function MyEvents(): array
    {
        $user = auth()->user();

        $seminars = $user->seminars;
        return ['seminars'=>$seminars];
    }
    public function MyEvent($id): array
    {
        $user = auth()->user();

        $seminar = Seminar::find($id);
        if (!$seminar) {
            return ['type' => 'error', 'msg' => 'This seminar is no longer available.'];
        }
        return ['type' => 'success', 'seminar' => $seminar];
    }

    public function markCourseCompleted($id, $year = null){
        $user = auth()->user();
        $year = $this->resolveEnrollmentYear($user->id, $id, $year);
        $userCourse = LessonUser::where('user_id',$user->id)
            ->where('lesson_id',$id)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->first();
        if (! $userCourse) {
            $userCourse = LessonUser::where('user_id',$user->id)
                ->where('lesson_id',$id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        if ($userCourse){
            if ($userCourse->completed_at == null){
                $userCourse->completed_at = \Carbon\Carbon::now();
                $userCourse->save();
            }
            return  $userCourse;
        }else{
            return null;
        }
    }
    public function getUserCourse($id, $year){
        $user = auth()->user();
        $year = $this->resolveEnrollmentYear($user->id, $id, $year);
        $userCourse = LessonUser::where('user_id',$user->id)
            ->where('lesson_id',$id)
            ->whereYear('created_at', $year)
            ->orderBy('created_at','desc')
            ->first();
        if (! $userCourse) {
            $userCourse = LessonUser::where('user_id',$user->id)
                ->where('lesson_id',$id)
                ->orderBy('created_at','desc')
                ->first();
        }
        return  $userCourse;
    }
    public function approvalNo($course, $catId, $course_completion_date, $state_id = null){
    
        $approvals = $course->approvals;

        if (count($approvals)){
          foreach ($approvals as $approval){
            
              if ($approval->approval_id == $catId 
              && Helper::checkApprovalValidity($course_completion_date, $approval->end_date) 
              && $approval->states()->where('state_id',$state_id)->exists() 
              && Helper::checkApprovalValidity2($course_completion_date, $approval->start_date)
            //   && $approval->Categories()->where('category_id',auth()->user()->category_id)->first()
              ){
              return $approval->approval_no;
                //     // dd($approval->approval_no);
                //   return $approvals[0]->approval_no;
              }
          }


        }
        // abort(404);
        return  null;
    }

    public function UploadUserFiles($request,$userId){

        $data = [
            'course_id'=>$request->course_id,
            'type'=>$request->type,
            'user_id'=>$userId

        ];
        $fileData =  $this->uploadAttachments($request);
        
        $data = array_merge($data,$fileData);

        $userFile = UserFiles::create($data);
        $notificationData = [
            'user'=>auth()->user(),
            'course'=>Lesson::find($request->course_id),
            'file'  => $fileData['file']

        ];
        Helper::sendNotificationSafely(Helper::adminUser(), new UserUploadAdminNotification($notificationData), 'user upload admin');
        return $userFile;

    }
    public function uploadAttachments($request){
        if ($file = $request->file('upload')) {

            $memeType = $file->getMimeType();
            $path = "uploads/userFiles";
            if(!is_dir($path)){
                mkdir($path, 0777, true);
            }
            $name = rand(0,9999).$file->getClientOriginalName();
            $name = str_replace(' ', '_', $name);
            $file->move($path, $name);
            return [
                'file' => $path.'/'.$name,
                'file_name' => $file->getClientOriginalName()
                ];


        }
    }

//    type 1 = radio
//    type 2 = checkbox
//    type 3 = Textarea
    public function saveUserExercise($request){

        $exercise = Quiz::find($request->quiz_id);
      //  dd($request->question);
        if ($request->question){
            foreach ($request->question as $key=>$val){
            $question = QuizQuestion::find($key);
          //  dd($question);
            $data = [
                'user_id'=>auth()->user()->id,
                'quiz_id'=>$request->quiz_id,
                'question_id'=>$question->id,
                'type'=>$question->type,
            ];
            if ($question->type == 1){
                $getAns = UserQuizAnswer::where($data)->first();
           //     dd($getAns);
                $data = array_merge($data,['answer_id'=>$val]);
               // dd($data);
                if ($getAns){

                    $update = UserQuizAnswer::where('id',$getAns->id)->update($data);
                //    dd($update);
                }else{
                    UserQuizAnswer::create($data);
                }
            }
            if ($question->type == 2){
                    $getAns = UserQuizAnswer::where($data)->delete();
                foreach ($val as $v){
                    $data = array_merge($data,['answer_id'=>$v]);
                    UserQuizAnswer::create($data);
                }
            }
            if ($question->type == 3){
                $getAns = UserQuizAnswer::where($data)->first();
                $data = array_merge($data,['answer'=>$val]);
                if ($getAns){
                    UserQuizAnswer::where('id',$getAns->id)->update($data);
                }else{
                    UserQuizAnswer::create($data);
                }
            }
        }
            return ['exercise'=>$exercise,'q'=>$exercise->questions,'request'=>$request];
        }else{
            return ['exercise'=>$exercise,'q'=>null,'request'=>$request];
        }
    }

    public function getCompletedChapterIds($userId, $lessonId, $year, $userChapters = null)
    {
        if ($userChapters === null) {
            $userChapters = $this->getUserLessonChapters($userId, $lessonId, $year);
        }

        $completed = UserQuiz::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->whereYear('created_at', $year)
            ->pluck('chapter_id')
            ->toArray();

        // Only a passed quiz (>= 75%) or, for chapters WITHOUT a quiz, enough study time
        // earns the checkmark. Time alone must never complete a chapter that has a quiz.
        foreach ($userChapters as $userChapter) {
            if ((float) $userChapter->quiz_percentage >= 75) {
                $completed[] = $userChapter->chapter_id;
                continue;
            }
            $chapter = $userChapter->chapter ?? Chapter::find($userChapter->chapter_id);
            $hasQuiz = $chapter && $chapter->quiz_id;
            if (! $hasQuiz && (float) $userChapter->time_spent >= 55) {
                $completed[] = $userChapter->chapter_id;
            }
        }

        return array_values(array_unique($completed));
    }

    public function adminUpdateChapterProgress($userId, $lessonId, $chapterId, $year, $minutes, $markQuizPassed = false)
    {
        $user = User::find($userId);
        $course = Lesson::find($lessonId);
        $chapter = Chapter::find($chapterId);

        if (! $user || ! $course || ! $chapter) {
            return ['type' => 'error', 'msg' => 'Student, course, or chapter not found.'];
        }

        $year = (int) $year;
        $data = [
            'user_id' => $userId,
            'lesson_id' => $lessonId,
            'chapter_id' => $chapterId,
        ];

        $record = LessonChapterUser::where($data)->whereYear('created_at', $year)->first();
        if (! $record) {
            $record = LessonChapterUser::where($data)->orderBy('created_at', 'desc')->first();
        }

        if (! $record) {
            $enrollment = LessonUser::where('user_id', $userId)
                ->where('lesson_id', $lessonId)
                ->whereYear('created_at', $year)
                ->orderBy('created_at', 'desc')
                ->first();

            $record = LessonChapterUser::create(array_merge($data, [
                'time_spent' => 0,
                'status' => 1,
                'created_at' => $enrollment ? $enrollment->created_at : now(),
            ]));
        }

        $record->time_spent = max(0, round((float) $minutes, 2));
        if ($markQuizPassed) {
            $record->quiz_percentage = 75;
        }
        $record->updated_at = now();
        $record->save();

        if ($markQuizPassed && $chapter->quiz_id) {
            $quizExists = UserQuiz::where('user_id', $userId)
                ->where('lesson_id', $lessonId)
                ->where('chapter_id', $chapterId)
                ->whereYear('created_at', $year)
                ->exists();

            if (! $quizExists) {
                UserQuiz::create([
                    'quiz_id' => $chapter->quiz_id,
                    'user_id' => $userId,
                    'lesson_id' => $lessonId,
                    'chapter_id' => $chapterId,
                    'user_percentage' => 75,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return [
            'type' => 'success',
            'msg' => 'Chapter progress updated to '.$record->time_spent.' mins for '.$chapter->title.'.',
        ];
    }
}
