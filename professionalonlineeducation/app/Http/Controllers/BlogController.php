<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class BlogController extends Controller
{

    public function categoriesLists(){
        $service = new BlogService();
        $lists = $service->category_list();
        $categories = $lists['data'];
        if ($lists['type'] == 'error'){
            return  view('admin.blog.categoriesList',compact('categories'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('admin.blog.categoriesList',compact('categories'));
        }else{
            return abort('500');
        }

    }

    public function categoriesAdd(){
        $pageData = array('type'=>'add','name'=>'Add Category');
        $category = (object)[];
        return  view('admin.blog.addCategory',compact('category','pageData'));
    }

    public function categoriesEdit($id){
        $pageData = array('type'=>'edit','name'=>'Edit Category');
        $service = new BlogService();
        $cat = $service->getCat($id);
        if ($cat['type'] == 'success'){
            $category = $cat['data'];
        }else{
            return abort('505');
        }

        return  view('admin.blog.addCategory',compact('category','pageData'));
    }

    public function categoriesSave(Request $request){
        $service = new BlogService();
        $save = $service->categoriesSave($request);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_blog_categories'))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_blog_categories'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function categoriesDelete($id){
        $service = new BlogService();
        $save = $service->categoriesDelete($id);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_blog_categories'))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_blog_categories'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function postLists(){
        $service = new BlogService();
        $lists = $service->blog_lists();
        $posts = $lists['data'];
        if ($lists['type'] == 'error'){
            return  view('admin.blog.postList',compact('posts'))->with('error','Something Went Wrong');
        }else if($lists['type'] == 'success'){
            return  view('admin.blog.postList',compact('posts'));
        }else{
            return abort('500');
        }
    }

    public function postAdd($post=false){
        $pageData = array('type'=>'add','name'=>'Add Posts');
        if (!$post){
            $post = (object)[];
        }
        $service = new BlogService();
        $categories = $service->category_list()['data'];
        return  view('admin.blog.addPost',compact('post','pageData','categories'));
    }

    public function postSave(Request $request){
        $service = new BlogService();
        $save = $service->postSave($request);
        if ($save['type'] == 'error'){
            Session::flash('error', $save['msg']);
            return $this->postAdd($save['data']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_blog_posts'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }

    public function postEdit($id){
        $pageData = array('type'=>'edit','name'=>'Edit Post');
        $service = new BlogService();
        $post = $service->getPost($id);

        if ($post['type'] == 'success'){
            $post = $post['data'];
        }else{
            return abort('505');
        }
        $service = new BlogService();
        $categories = $service->category_list()['data'];

        return  view('admin.blog.addPost',compact('post','pageData','categories'));
    }


    public function postDelete($id){
        $service = new BlogService();
        $save = $service->PostDelete($id);
        if ($save['type'] == 'error'){
            return Redirect::to(route('admin_blog_posts'))->with('error', $save['msg']);
        }else if($save['type'] == 'success'){
            return Redirect::to(route('admin_blog_posts'))->with('info', $save['msg']);
        }else{
            return abort('500');
        }
    }





}
