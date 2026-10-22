<?php
/**
 * Created by PhpStorm.
 * User: musha
 * Date: 17/07/2020
 * Time: 1:58 AM
 */

namespace App\Services;

use App\User;
use Auth;

class ClientService
{


    Public function UpdateProfile($request){
        try{
        $model = new User;
        $exceptRequest = array(
            '_method','_token','email'
        );
        $userId = Auth::id();

        $model->updateProfile($request,$userId,$exceptRequest);
        return true;
        }catch (\Exception $exception){
//            $data = ['message' => $exception->getMessage()];
//            $mail = Mail::to(config('settings.map.dev_email'))->send(new TestEmail($data));
            $request->session()->flash('error', 'Some Thing went wrong!');
            return false;
        }

    }

}