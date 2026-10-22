<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebinarService;
use Illuminate\Support\Facades\Redirect;

class WebinarController extends Controller
{
    
    public function lists(){
        $service = new WebinarService();
        $lists = $service->WebinarList();
        $webinars = $lists['data'];
        if ($lists['type'] == 'error'){
            return  view('admin.webinar.list',compact('webinars'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('admin.webinar.list',compact('webinars'));
        }else{
            return abort('500');
        }

    }

    public function add(){
        $pageData = array('type'=>'add','name'=>'Add Webinar');
        $webinar = (object)[];
        return  view('admin.webinar.add',compact('webinar','pageData'));
    }

    public function edit($id){
        $pageData = array('type'=>'edit','name'=>'Edit Category');
        $service = new WebinarService();
        $cat = $service->getWebinar($id);
        if ($cat['type'] == 'success'){
            $webinar = $cat['data'];
        }else{
            return abort('505');
        }

        return  view('admin.webinar.add',compact('webinar','pageData'));
    }

    public function save(Request $request){
        $service = new WebinarService();
        $save = $service->WebinarSave($request);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_webinars'))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_webinars'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function delete($id){
        $service = new WebinarService();
        $save = $service->WebinarDelete($id);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_webinars'))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_webinars'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }



}
