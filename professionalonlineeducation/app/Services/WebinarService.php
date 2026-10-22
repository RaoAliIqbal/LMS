<?php

namespace App\Services;

use App\User;
use Auth;
use Notification;
use App\Models\Webinar;
use App\Http\Helpers\General\Helper;

class WebinarService
{


    public function WebinarList($search=false){
        try{
            $model = new Webinar();
            $list = $model::select('*');

        if ($search){
            $list->where('title','like','%'.$search.'%');
        }
        $list = $list->get();

           return array('type'=>'success','data' => $list);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }
    }

    public function WebinarSave($request){
        try{
            $model = new Webinar();
            $exceptRequest = array(
                '_method','_token','id','thumbnail_image'
            );
            $request = $this->manageAttachments($request);

            if (isset($request->id)){
                $cat =  $model::where('id',$request->id)->update($request->except($exceptRequest));
            }else{
                $cat =  $model::create($request->except($exceptRequest));
            }

            return array('type'=>'success','msg' => 'Webinar successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');

        }
    }

    public function getWebinar($id){
        try{
            $model = new Webinar();
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

    public function WebinarDelete($id){
        try{
            $model = new Webinar();
            $webinar = $model::find($id);
            if (file_exists($webinar->thumbnail)) {
                unlink($webinar->thumbnail);
            }
            if ($webinar){
                $webinar->delete();
                return array('type'=>'success','msg' => 'Webinar successfully deleted');
            }else{
              abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }


    public function manageAttachments($request){

        if ($file = $request->file('thumbnail_image')) {

            $memeType = $file->getMimeType();
            $path = "uploads/webinar";
            if(!is_dir($path)){
                mkdir($path, 0777, true);
            }
            $name = rand(0,9999).$file->getClientOriginalName();
            $file->move($path, $name);
            $request->request->add(['thumbnail' => $path.'/'.$name]);

        }
        return $request;
    }

    public function getRandomWebinar($limit){
        $model = new Webinar();
        $posts = $model::inRandomOrder()->limit($limit)->get();
        return $posts;
    }



}