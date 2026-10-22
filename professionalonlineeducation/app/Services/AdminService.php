<?php

namespace App\Services;

use App\MailTemplate;
use App\Project;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Notification;
use App\Notifications\ProjectArchive;

class AdminService
{


    Public function UpdateProfile($request,$id = false){
        try{
        $model = new User;

        if ($id){
            $userId = $id;
        }else{
            $userId = Auth::id();
        }
            if ($request->new_password != null){
                if ($request->new_password != $request->con_password){
                    return array('type'=> 'error','msg'=>'Your Password and Confirm Password did not matched');
                }
                if (Hash::check($request->new_password, Auth::user()->password)) {
                    return array('type'=>'error','msg' => 'New Password cannot be same as Current Password');
                }
                $user = User::find(Auth::user()->id)->update(['password' => Hash::make($request->new_password)]);
            }
            $exceptRequest = array(
                'new_password','con_password','_method','_token','id'
            );
            $model->updateProfileAdmin($request,$userId,$exceptRequest);
            return array('type'=>'success','msg' => 'Your Profile has been Updated!');
        }catch (\Exception $exception){
//            $data = ['message' => $exception->getMessage()];
//            $mail = Mail::to(config('settings.map.dev_email'))->send(new TestEmail($data));
            return array('type'=>'error','msg' => 'Some Thing went wrong!');

        }

    }

    Public function UpdateUserPwd($request){
        $UserModel = new User();

        $user = $UserModel::find($request->id);

        if (Hash::check($request->new_password, $user->password)) {
            return array('type'=>'error','msg' => 'New Password cannot be same as Current Password');
        }if ($request->new_password != $request->con_password) {
            return array('type'=>'error','msg' => 'Confirm Password should be same as New Password');
        }
//        if (!Hash::check($request->current_password, $user->password)) {
//            return array('type'=>'error','msg' => 'Your Current Password does not match with the password you provided');
//        }
        $user = User::find($user->id)->update(['password' => Hash::make($request->new_password),'real_password'=>$request->new_password]);
        return array('type'=>'success','msg' => 'Your Password has been Updated!');

    }

    public function userDelete($id): array
    {
        $UserModel = new User();
        $user = $UserModel::find($id);
        $user->linceses()->delete();
        $user->seminars()->delete();
        $user->paymentLogs()->delete();
        $user->clinic()->delete();
        foreach ($user->courses as $userCourse){
            $user->userCourseFiles($userCourse->course_id)->delete();
            $userCourse->delete();
        }
        $user->delete();
        return array('type'=>'success','msg' => 'Student Deleted Successfully');
    }
    public function changeUserStatus($id,$status){
        $userModel = new User();
        $user = $userModel::find($id);
        $user->status = $status;
        $user->save();
        return array('type'=>'success','msg' => 'Student Deactivate Successfully');
    }



    Public function updateNotification($request){

        $UserModel = new User();
        $ProjectModel = new Project();
        $model = new MailTemplate();
        $exceptRequest = array(
            '_token','id','_method'
        );
        $data = array(
            'title'=>$request->title,
            'subject'=>$request->subject,
            'body'=>$request->message,

        );
        $model::where('id',$request->id)->update($data);
        return array('type'=>'success','msg' => 'Notification has been Updated!');

//
    }


}
