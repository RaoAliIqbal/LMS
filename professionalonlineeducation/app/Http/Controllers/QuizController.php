<?php

namespace App\Http\Controllers;

use App\Http\Helpers\General\Helper;
use App\Models\LessonChapterUser;
use App\Notifications\QuizNotification;
use Illuminate\Http\Request;
use App\Services\QuizService as Service;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;
use Carbon\Carbon;
use Auth;
use App\User;
use Illuminate\Support\Facades\Notification;

class QuizController extends Controller
{

    public function viewExercises(){
        $service = new Service();
        $lists = $service->exercisesList();
        $quizzes = $lists['data'];
        if ($lists['type'] == 'error'){
            return  view('admin.exercises.list',compact('quizzes'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('admin.exercises.list',compact('quizzes'));
        }else{
            return abort('500');
        }
    }
    public function exerciseAdd($quiz=false){
        $pageData = array('type'=>'add','name'=>'Add Exercise');
        if (!$quiz){
            $quiz=(object)[];
        }

        return  view('admin.exercises.add',compact('quiz','pageData'));
    }
    public function exerciseEdit($id){
        $pageData = array('type'=>'edit','name'=>'Edit Exercise');
        $service = new Service();
        $quiz = $service->getQuizById($id);
        return  view('admin.exercises.add',compact('quiz','pageData'));
    }
    public function exerciseSave(Request $request){
        $service = new Service();
        $save = $service->exerciseSave($request);
        if ($save['type'] == 'error'){
            Session::flash('error', $save['msg']);
            return $this->exerciseAdd($request);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_exercises'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function addQuestionExercise($id,$question=false){
        $pageData = array('type'=>'add','name'=>'Add Question');
        if (!$question){
            $question=(object)[];
            $question->answers = [];
        }
        return  view('admin.exercises.addQuestion',compact('question','pageData','id'));
    }
    public function editQuestionExercise($id,$qid){
        $pageData = array('type'=>'edit','name'=>'Edit Question');
        $service = new Service();
        $question = $service->getQuestionById($qid);
        return  view('admin.exercises.addQuestion',compact('question','pageData','id'));
    }
    public function exerciseQuestionSave(Request $request){

        $service = new Service();
        $save = $service->questionSave($request);
        if ($save['type'] == 'error'){
            Session::flash('error', $save['msg']);
            return redirect()->back() ; //$this->addQuestionExercise($request->quiz_id,$request);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_questions_exercise',$request->quiz_id))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }


    public function questionsExercise($id){
        $service = new Service();
        $lists = $service->questionList($id);
        $questions = $lists['data'];
        $quiz = $lists['quiz'];
        if ($lists['type'] == 'error'){
            return  view('admin.exercises.questionList',compact('questions','quiz'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('admin.exercises.questionList',compact('questions','quiz'));
        }else{
            return abort('500');
        }
    }






    public function viewQuizzes(){
        $service = new Service();
        $lists = $service->quizList();
        $quizzes = $lists['data'];
        if ($lists['type'] == 'error'){
            return  view('admin.quiz.list',compact('quizzes'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('admin.quiz.list',compact('quizzes'));
        }else{
            return abort('500');
        }
    }

    public function quizAdd($quiz=false){
        $pageData = array('type'=>'add','name'=>'Add Quiz');
        if (!$quiz){
            $quiz=(object)[];
        }
        return  view('admin.quiz.addQuiz',compact('quiz','pageData'));
    }
    public function quizEdit($id){
        $pageData = array('type'=>'edit','name'=>'Edit Quiz');
        $service = new Service();
        $quiz = $service->getQuizById($id);
        return  view('admin.quiz.addQuiz',compact('quiz','pageData'));
    }

    public function quizSave(Request $request){
        $service = new Service();
        $save = $service->quizSave($request);
        if ($save['type'] == 'error'){
            Session::flash('error', $save['msg']);
            return $this->quizAdd($request);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_quizzes'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }
    public function quizDelete($id){
        $service = new Service();
        $delete = $service->quizDelete($id);
        if ($delete['type'] == 'error'){
            return Redirect::back()->with('error', $delete['msg']);
        }else if($delete['type'] == 'success'){
            return Redirect::back()->with('info', $delete['msg']);
        }else{
            return abort('500');
        }
    }


    public function addQuestion($id,$question=false){
        $pageData = array('type'=>'add','name'=>'Add Question');
        if (!$question){
            $question=(object)[];
            $question->answers = [];
        }

        return  view('admin.quiz.addQuestion',compact('question','pageData','id'));
    }
    public function editQuestion($id,$qid){
        $pageData = array('type'=>'edit','name'=>'Edit Question');
        $service = new Service();
        $question = $service->getQuestionById($qid);

        return  view('admin.quiz.addQuestion',compact('question','pageData','id'));
    }
    public function questionSave(Request $request){
        $service = new Service();
        $save = $service->questionSave($request);
        if ($save['type'] == 'error'){
            Session::flash('error', $save['msg']);
            return $this->addQuestion($request->quiz_id,$request);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_questions',$request->quiz_id))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }
    public function questions($id){
        $service = new Service();
        $lists = $service->questionList($id);
        $questions = $lists['data'];
        $quiz = $lists['quiz'];
        if ($lists['type'] == 'error'){
            return  view('admin.quiz.questionList',compact('questions','quiz'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('admin.quiz.questionList',compact('questions','quiz'));
        }else{
            return abort('500');
        }
    }
    public function questionDelete($id,$qid){
        $service = new Service();
        $delete = $service->questionDelete($qid);
        if ($delete['type'] == 'error'){
            return Redirect::back()->with('error', $delete['msg']);
        }else if($delete['type'] == 'success'){
            return Redirect::back()->with('info', $delete['msg']);
        }else{
            return abort('500');
        }
    }


    // User

    public function UserQuizzes(){
        $service = new Service();
        $lists = $service->quizList();
        $quizzes = $lists['data'];
        if ($lists['type'] == 'error'){
            return  view('client.quiz.quizzes',compact('quizzes'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('client.quiz.quizzes',compact('quizzes'));
        }else{
            return redirect()->route('client_Courses')->with('error', 'Quizzes could not be loaded.');
        }
    }
    public function UserSaveQuiz(Request $request){
        try {
            return $this->saveQuizSubmission($request);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error('Quiz submit failed: '.$e->getMessage(), [
                'user_id' => auth()->id(),
                'course_id' => $request->input('courseId'),
                'chapter_id' => $request->input('chapterId'),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('client_Course', [
                    'id' => $request->input('courseId'),
                    'year' => $request->input('year'),
                ])
                ->with('error', 'Your quiz could not be submitted. Your study time was saved — please try again or call 1-888-900-3382.');
        }
    }

    protected function saveQuizSubmission(Request $request)
    {
        if ($request->filled('study_time')) {
            try {
                (new \App\Services\EducationService())->addTime(new Request([
                    'lesson_id' => $request->input('courseId'),
                    'chapter_id' => $request->input('chapterId'),
                    'time' => (int) $request->input('study_time'),
                    'year' => $request->input('year'),
                ]));
            } catch (\Throwable $e) {
                \Log::warning('Quiz pre-save time failed: '.$e->getMessage(), ['user_id' => auth()->id()]);
            }
        }

      //  return redirect()->route('chapterComplete',['id'=>$request->courseId,'chapterId'=>$request->chapterId,'per'=>'25']);
        $EduService = new \App\Services\EducationService();
        $service = new Service();
        $test = $service->quizTest($request);
        $result = $test['msg'];
        $per = $test['per'];
        $quiz = $test['quiz'];
        $readableQuiz = $service->readableQuiz($request);
        
        if ($test['type'] == 'error'){
            return redirect()->route('client_Course',['id'=>$request->courseId,'chapterId'=>$request->chapterId, 'year'=>$request->year])->with('error','Something Went Wrong');
        }else if($test['type'] == 'success'){
            Helper::sendNotificationSafely(User::find(4), new QuizNotification(['request'=>$readableQuiz,'test'=>$test,'user'=>auth()->user()]), 'quiz submit admin');
            $chapter = $EduService->getChapterById($request->chapterId);
            if ($test['msg'] == 'pass'){
                return redirect()->route('chapterComplete',['id'=>$request->courseId,'chapterId'=>$request->chapterId,'year'=>$request->year,'per'=>$per]);
            }else{

                return redirect()
                    ->route('client_Course',['id'=>$request->courseId, 'year'=>$request->year])
                    ->with('QuizError',[
                        'msg'=>'You need <b>'.$test['passingPer'].'% </b> for passing <b>'.$chapter->title.'</b> Quiz. You got '.$per.'%',
                        'id'=>$request->courseId,
                        'chapterId'=>$request->chapterId,
                        'previousQuiz'=>$readableQuiz,
                    ]);
            }

//            return  view('client.quiz.result',compact('result','per','quiz'));
        }else{
            return redirect()->route('client_Courses')->with('error', 'Quiz could not be saved. Please try again.');
        }

    }
    public function quiz($id,$chapterId, $year){
        
        $service = new \App\Services\EducationService();
        $user = auth()->user();
        $data = $service->myCourseChapter($id,$chapterId,$user, $year);
        $data['quiz']=$data['chapter']->quiz;
        $data['id'] = $id;
        $data['chapterId'] = $chapterId;
        $data['isQuizAllowed'] = $this->isQuizAllowed($id,$chapterId, $year);
        $data['year']    = $year ?? null;
        if ($data['type'] == 'error'){
            return Redirect::to(route('client_Courses'))->with('Error', $data['msg']);
        }else if($data['type'] == 'success'){
//            dd($data['quiz']);
            return view('client.quiz.startQuizPage')->with($data);
        }else{
            return redirect()->route('client_Courses')->with('error', 'Quiz could not be loaded.');
        }

    }
    public function isQuizAllowed($id,$chapterId, $year){
        $data = [
            'lesson_id'=>$id,
            'chapter_id'=>$chapterId,
            'user_id'=>auth()->user()->id,
        ];
        $lessonCourse = LessonChapterUser::where($data)->whereYear('created_at', $year)->first();
        if (!$lessonCourse) {
            $lessonCourse = LessonChapterUser::where($data)->orderBy('created_at', 'desc')->first();
        }
        if (!$lessonCourse){ return false; }
        if ($lessonCourse->time_spent >= 55){
            return true;
        }
        return false;
    }
    public function TakeQuiz($id,$chapterId, $year){
        if ($this->isQuizAllowed($id,$chapterId, $year) == false){
            return redirect()->route('myCourseChapterLearning', ['id' => $id, 'chapterId' => $chapterId, 'year' => $year])
                ->with('error', 'Complete at least 55 minutes of study time before taking the quiz.');
        }
        $service = new \App\Services\EducationService();
        $user = auth()->user();
        $data = $service->myCourseChapter($id,$chapterId,$user, $year);
        $data['quiz']=$data['chapter']->quiz;
        $data['year'] = $year;
         if ($data['type'] == 'error'){
            return Redirect::to(route('client_Courses'))->with('Error', $data['msg']);
        }else if($data['type'] == 'success'){
//            dd($data['quiz']);
            return view('client.quiz.quizPage')->with($data);
        }else{
            return redirect()->route('client_Courses')->with('error', 'Quiz could not be loaded.');
        }

    }





    public function getCertificate($id){

        $service = new Service();
        $quiz = $service->getQuizById($id);
        if ($quiz['type'] == 'error'){
            return abort('404');
        }
        $quizName = $quiz->title;
        $name = Auth::user()->FullName;
        $verify = $service->isCertificateBelongsToUser($id);
        if ($verify){
            $this->generatePdf($quizName,$name);
        }else{
            return abort('404');
        }
        return Redirect::route('client_certificates');

    }
    public function generatePdf($quizName,$name){
        $pdf = new Fpdi();
        $file = public_path('assets/certificate.pdf');

        $pdf->setSourceFile($file);
        $pdf->AddPage();

        $tplIdx = $pdf->importPage(1);

        $pdf->useTemplate($tplIdx, 0, 0, 250, 200, true);

        $pdf->SetFont('Arial');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetXY(115, 145);
        $pdf->Write(0, $quizName);

        $pdf->SetFont('Arial');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetXY(81, 133);
        $pdf->Write(0, $name);

        $now = Carbon::now()->format('y');
        $y = str_split($now,'1')[1];

        $pdf->SetFont('Arial');
        $pdf->SetFontSize('16');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetXY(140, 152);
        $pdf->Write(0, $y);

        $pdf->Output('certificate.pdf', 'D');

    }
    public function certificates(){

        $service = new Service();
        $lists = $service->certificates();
        $certificates = $lists['data'];

        if ($lists['type'] == 'error'){
            return  view('client.quiz.certificates',compact('certificates'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('client.quiz.certificates',compact('certificates'));
        }else{
            return redirect()->route('client_Courses')->with('error', 'Certificates could not be loaded.');
        }

    }

}
