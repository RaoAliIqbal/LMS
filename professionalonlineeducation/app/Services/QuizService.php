<?php

namespace App\Services;

use App\Models\LessonChapterUser;
use App\User;
use Auth;
use Notification;
use App\Models\Quiz as model;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionAnswer;
use App\Models\UserQuiz;
use App\Http\Helpers\General\Helper;


class QuizService
{

    public function exercisesList(){
        try{
            $model = new model();
            $list = $model::where('type','2')->get();
            return array('type'=>'success','data' => $list);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }
    }
    public function exerciseSave($request){
        $request->request->add(['type' => 2]);
        $request->request->add(['passing_percentage' => 100]);
        return $this->quizSave($request,'Exercise');
    }



    public function quizList(){
        try{
            $model = new model();
            $list = $model::where('type','1')->get();
            return array('type'=>'success','data' => $list);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }
    }

    public function quizSave($request,$type='Quiz'){
        try{

            if ($request->passing_percentage > 100){
                return array('type'=>'error','msg' => 'Passing Percentage can not exceed 100%');
            }
            $model = new model();
            $exceptRequest = array(
                '_method','_token','id'
            );

            if (isset($request->id)){
                $cat =  $model::where('id',$request->id)->update($request->except($exceptRequest));
            }else{
                $cat =  $model::create($request->except($exceptRequest));
            }

            return array('type'=>'success','msg' => $type.' successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');

        }
    }

    public function quizDelete($id){
        try{
            $model = new model();
            $quiz = $model::find($id);
            if ($quiz){
                $quiz->delete();
                return array('type'=>'success','msg' => 'Quiz successfully deleted');
            }else{
                abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

    public function getQuizById($id){
        try{
            $model = new model();
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

    public function questionSave($request){

        try{

           $model = new QuizQuestion();
           $ansModel = new QuizQuestionAnswer();
           if ($request->type != 4){
               $validate = $this->validateQuestions($request);

//               if ($validate['status'] == false){
//                   return array('type'=>'error','msg' => $validate['msg']);
//               }

           }

           $exceptRequest = array(
               '_method','_token','id','answer','trueAnswer','multi_answer','multiTrueAnswer','text_answer','match_answer','answers'
           );

           if (isset($request->id)){
               $model::where('id',$request->id)->update($request->except($exceptRequest));
               $question = $model::find($request->id);

               $ansModel::where('question_id',$request->id)->delete();
           }else{
               $question = $model::create($request->except($exceptRequest));
           }

           if ($question->type === 1){
              
               $i = 1;
               $true = $request->trueAnswer;
             
               foreach ($request->answers as $answer){
                   $data = array(
                       'question_id'=>$question->id,
                       'answer'=>$answer,
                   );
                   if ($i == $true){
                       $data['is_true'] = '1';
                   }

                   $ansModel::create($data);

                   $i++;

               }

           }

           if ($question->type == 2){
               $i = 1;
               $true = $request->multiTrueAnswer;

               foreach ($request->multi_answer as $answer){
                   $data = array(
                       'question_id'=>$question->id,
                       'answer'=>$answer,
                   );
                   if (in_array($i,$true)){
                       $data['is_true'] = '1';
                   }
                   $ansModel::create($data);

                   $i++;

               }

           }
           if ($question->type == 3){
           $data = array(
               'question_id'=>$question->id,
               'answer'=>$request->text_answer,
               'is_true'=>'1',

           );
           $ansModel::create($data);
       }
           if ($question->type == 4){

               $i = 1;
               $true = $request->multiTrueAnswer;

               foreach ($request->match_answer as $answer){
                   $data = array(
                       'question_id'=>$question->id,
                       'answer'=>$answer,
                       'answer_two'=>$request->answer[$i]
                   );
                   $ansModel::create($data);
                   $i++;
               }
           }
           return array('type'=>'success','msg' => 'Question successfully saved');
       }catch (\Exception $exception){
           $helper = new Helper();
           $helper::errorHandling($exception);
           return array('type'=>'error','msg' => 'Something went wrong, Please try again later');

       }

    }

    private function validateQuestions($request){

        if ($request->type == 1){
            if (isset($request->answer)){
                $count = count($request->answer);
//                if ($count != 3){
//                    return array('status'=>false,'msg'=>'At least 3 answers required');
//                }
                if (!isset($request->trueAnswer)){

                    return array('status'=>false,'msg'=>'True Answers Required');
                }
                return array('status'=>true);
            }else{
                return array('status'=>false,'msg'=>'Answers Required');
            }
        }else if($request->type == 2){

            if (isset($request->multi_answer)){
//                $count = count($request->multi_answer);
//                if ($count != 3){
//                    return array('status'=>false,'msg'=>'At least 3 answers required');
//                }
                if (count($request->multiTrueAnswer) < 1){

                    return array('status'=>false,'msg'=>'True Answers Required');
                }

                return array('status'=>true);
            }else{
                return array('status'=>false,'msg'=>'Answers Required');
            }
        }else if($request->type == 3){
            if (isset($request->text_answer)){
                return array('status'=>true);
            }else{
                return array('status'=>false,'msg'=>'Answers Required');
            }
        }else{
            return false;
        }

    }

    public function questionList($id){
        try{
            $model = new model();
            $quiz = $model::find($id);
            $list = $quiz->questions;
            return array('type'=>'success','data' => $list,'quiz'=>$quiz);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }
    }

    public function getQuestionById($id){
        try{
            $model = new QuizQuestion();
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

    public function questionDelete($id){
        try{
            $model = new QuizQuestion();
            $ques = $model::find($id);

            if ($ques){

                if (count($ques->answers)){
                    foreach ($ques->answers as $ans){
                        $ans->delete();
                    }
                }
                $ques->delete();
                return array('type'=>'success','msg' => 'Question successfully deleted');
            }else{
                abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

    public function quizPage($id){
        $model = new model();
        $quiz = $model::find($id);
        $data = array();
        if (!$quiz){
            return array('type'=>'error','data' => array());
        }
        $data['quiz'] = $quiz;
        $data['type'] = 'success';

        return $data;

    }
    public function readableQuiz($request): array
    {
        $quiz = [];
        $questionModel = new QuizQuestion();
        $answerModel = new QuizQuestionAnswer();
        foreach ($request->ans as $key=>$val){
            $question = $questionModel::find($key);

            if(is_array($val)){
                $MultiAnsData = [];
                foreach ($val as $answerId){
                    $answer = $answerModel::find($answerId);
                    $MultiAnsData[] = [$answer->answer,'is_true'=>$answer->is_true];
                }

                $data= [
                    'type'=>'multi',
                    $question->question => $MultiAnsData
                ];
            }else{

                $answer = $answerModel::find($val);
                $data= [
                    'type'=>'single',
                    $question->question => ['answer'=>$answer->answer,'is_true'=>$answer->is_true]
                ];


            }
            $quiz[] = $data;


         }

         return $quiz;
    }

    public function quizTest($request){
        
     try{
        $model = new model();
        $questionModel = new QuizQuestion();
        $quiz = $model::find($request->quizId);
        $totalMarks = 0;
        $quizMarks = 100;//$this->getQuizMarks($quiz);

        if ($request->ans == null){
            return array('type'=>'success','msg' => 'fail','per'=>0,'quiz'=>$quiz,'passingPer'=>$quiz->passing_percentage);
        }
        foreach ($request->ans as $key=>$val){
            $question = $questionModel::find($key);
//            dd($question);
            if ($question->type == 2){
                $correctIds = array();
                foreach ($question->CorrectAnswers as $ans){
                    array_push($correctIds,$ans->id);
                }
                $correctAns = 0;
                foreach ($val as $ansId){
                    if (in_array($ansId,$correctIds)){
                        $correctAns = $correctAns+1;
                    }
                }
//                if ($key == 19){
//                    dd($correctAns);
//                }
                if (count($val) ==  $correctAns){
                    if (count($correctIds) == $correctAns){
                        $totalMarks = $totalMarks + '25';
                    }
                }
            }
            if ($question->type == 1){
                $correctIds = array();
                foreach ($question->CorrectAnswers as $ans){
                    array_push($correctIds,$ans->id);
                }
                if ($correctIds[0] == $val){
                    $totalMarks = $totalMarks + 25;
                }
            }
            if ($question->type == 3){
                $correctAns = $question->CorrectAnswers;
                if ($correctAns[0]->answer == $val){
                    $totalMarks = $totalMarks + '25';
                }
            }
        }
        $per = $totalMarks/$quizMarks;
        $per = $per*100;
        $per = round($per);
        
        $LessonChapterUser = new LessonChapterUser();
        $LessonChapterUser->updatePer($request,$per);
        if ($per >= 75){
//dd('h12');
            $this->addQuizUser($quiz,$request->chapterId,$request->courseId,$per, $request->year);

            return array('type'=>'success','msg' => 'pass','per'=>$per,'quiz'=>$quiz,'passingPer'=>$quiz->passing_percentage);
        }else{

            return array('type'=>'success','msg' => 'fail','per'=>$per,'quiz'=>$quiz,'passingPer'=>$quiz->passing_percentage);
        }
     }catch (\Exception $exception){
         $helper = new Helper();
         $helper::errorHandling($exception);

         return array('type'=>'error','per' => array(),'msg'=>'Something went wrong, Please try again later.','quiz'=>'','passingPer'=>'');

     }

    }

    private function addQuizUser($quiz,$chapterId,$courseId,$per, $year){

        $userId = Auth::user()->id;
        $year = $year ?: date('Y');
        $data = array(
          'user_id'=>$userId,
          'lesson_id'=>$courseId,
          'chapter_id'=>$chapterId,
          'quiz_id'=>$quiz->id,
          'user_percentage'=>$per,
          'status'=>'1',
          'created_at'  => $createdAt = now()->setYear($year),
        );
        $model = new UserQuiz();
        // Scope to the enrollment year so a pass from a previous year does not block saving this year's pass.
        $check = $model::where('user_id',$userId)->where('quiz_id',$quiz->id)->where('lesson_id', $courseId)
            ->whereYear('created_at', (int) $year)->count();
        
        if ($check == 0){
            $create = $model::create($data);
            if ($create){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }


    }

    private function getQuizMarks($quiz){
        $totalMarks = 0;
        foreach ($quiz->questions as $ques){
            $totalMarks = $totalMarks+$ques->mark;
        }
        return $totalMarks;

    }

    public function certificates(){
        try{
        $userId = Auth::user()->id;
        $model = new UserQuiz();
        $list = $model::where('user_id',$userId)->get();

        return array('type'=>'success','data' => $list);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }

    }

    public function isCertificateBelongsToUser($quizId){
        $userId = Auth::user()->id;
        $model = new UserQuiz();
        $check = $model::where('user_id',$userId)->where('quiz_id',$quizId)->count();
        if ($check == 0){
            return false;
        }else{
             return true;
        }


    }

}
