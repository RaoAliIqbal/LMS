<?php
/**
 * Created by PhpStorm.
 * User: musha
 * Date: 17/07/2020
 * Time: 1:58 AM
 */

namespace App\Services;

use App\Models\UserClinic;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Helpers\General\Helper;
class ProfileService
{


    Public function UpdateProfile($request){

        try{
        $model = new User;
        $exceptRequest = array(
            '_method','_token','email','oldpassword','newpassword'
        );
        $userId = Auth::id();
        if (isset($request->oldpassword) && isset($request->newpassword)){
            $pattern = "/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\x]).*$/";
            if (!Hash::check($request->oldpassword, Auth::user()->password)) {
                return array('type'=>'error','msg' => 'Your Current Password does not match with the password you provided');
            }
            if (!preg_match($pattern, $request->newpassword)){
                return array('type'=>'error','msg' => 'Your Password must have one capital character and one number');
            }
           User::find(Auth::user()->id)->update(['password' => Hash::make($request->newpassword)]);


        }
        $model->updateProfile($request,$userId,$exceptRequest);
            return array('type'=>'success','msg' => 'Profile Updated');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());
        }

    }
    Public function SaveClinic($request){


        try{
        $userModel = new User;
        $userClinicModel = new UserClinic;
        $exceptRequest = array(
            '_method','_token','email','oldpassword','newpassword'
        );
        $userId = Auth::id();
            $request->request->add(['user_id'=> $userId]);

        $userClinic = $userClinicModel::where('user_id',$userId)->first();
        if ($userClinic == null){
            $userClinicModel::create($request->except($exceptRequest));
        }else{
            $userClinicModel::where('id',$userClinic->id)->update($request->except($exceptRequest));
        }
            return array('type'=>'success','msg' => 'Clinic Details Saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());
        }

    }

    Public function updateProfileImg($request){
        try{
        $model = new User;
        $exceptRequest = array(
            '_method','_token','email','profileImg','newpassword'
        );
        $userId = Auth::id();
        if($request->profileImg){
            $request = $this->manageAttachments($request);

           $model::where('id',$userId)->update($request->except($exceptRequest));

        }

            return array('type'=>'success','msg' => 'Profile Updated');
        }catch (\Exception $exception){
            // $helper = new Helper();
            // $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());
        }

    }
    public function manageAttachments($request){

        if ($file = $request->file('profileImg')) {

            $memeType = $file->getMimeType();
            $path = "uploads/profiles";
            if(!is_dir($path)){
                mkdir($path, 0777, true);
            }
            $name = rand(0,9999).$file->getClientOriginalName();
            $file->move($path, $name);
            $request->request->add(['img' => $path.'/'.$name]);

        }
        return $request;
    }


    Public function UpdatePassword($request){

        if (Hash::check($request->new_password, Auth::user()->password)) {
            return array('type'=>'error','msg' => 'New Password cannot be same as Current Password');
        }
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return array('type'=>'error','msg' => 'Your Current Password does not match with the password you provided');
        }
        $user = User::find(Auth::user()->id)->update(['password' => Hash::make($request->new_password)]);
        return array('type'=>'success','msg' => 'Your Password has been Updated!');


    }

}
