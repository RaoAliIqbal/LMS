<?php

namespace App\Services;

use App\User;
use Auth;
use Notification;
use App\Models\Evaluation as model;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionAnswer;
use App\Models\UserQuiz;
use App\Http\Helpers\General\Helper;

class EvaluationService
{

    public function list(){
        try{
            $model = new model();
            $list = $model::where('status','1')->get();
            return array('type'=>'success','data' => $list);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }
    }
    public function save($request){
//        try{

            $model = new model();
            $exceptRequest = array(
                '_method','_token','id','question','type'
            );
            $questions = [];
            $i = 0;
            foreach ($request->question as $question){
                $data= [
                    'question'=>$question,
                    'type'=>$request->type[$i]
                ];
                array_push($questions,$data);
                $i++;
            }

            $request->request->add(['questions' => json_encode($questions)]);
            if (isset($request->id)){
                $cat =  $model::where('id',$request->id)->update($request->except($exceptRequest));
            }else{
                $cat =  $model::create($request->except($exceptRequest));
            }

            return array('type'=>'success','msg' => 'Evaluation successfully saved');
//        }catch (\Exception $exception){
//            $helper = new Helper();
//            $helper::errorHandling($exception);
//            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');
//
//        }
    }
    public function getEvaluation($id){
        try{
            $model = new model();
            $cat = $model::find($id);
            if ($cat){
                $object = (object)[];
                $object->id = $cat->id;
                $object->title = $cat->title;
                $object->questions = json_decode($cat->questions);
                return array('type'=>'success','data' => $object);
            }else{
                abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

}
