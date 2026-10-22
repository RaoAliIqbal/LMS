<?php

namespace App\Services;

use App\User;
use Auth;
use Notification;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Http\Helpers\General\Helper;

class BlogService
{


    public function category_list(){
        try{
        $model = new BlogCategory();
        $list = $model::all();
           return array('type'=>'success','data' => $list);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }
    }

    public function categoriesSave($request){
        try{
        $model = new BlogCategory();
            $exceptRequest = array(
                '_method','_token','id'
            );
            $request = $this->addCategorySlug($request);

            if (isset($request->id)){
                $cat =  $model::where('id',$request->id)->update($request->except($exceptRequest));
            }else{
                $cat =  $model::create($request->except($exceptRequest));
            }

            return array('type'=>'success','msg' => 'Category successfully saved');
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','msg' => 'Something went wrong, Please try again later');

        }
    }
    private function addCategorySlug($request){

        $url = strtolower($request->category_name);
        $url = str_replace(' ','-',$url);
        $url = str_replace(' ','-',$url);
        $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
        $request->request->add(['category_slug' =>$url]);
        return $request;
    }

    public function getCat($id){
        try{
            $model = new BlogCategory();
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

    public function categoriesDelete($id){
        try{
            $model = new BlogCategory();
            $cat = $model::find($id);

            if ($cat){
                $cat->delete();
                return array('type'=>'success','msg' => 'Category successfully deleted');
            }else{
              abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

    public function blog_lists($CatSlug=false,$search=false){
        try{
            $model = new BlogPost();
            $CatModel = new BlogCategory();
            $title = 'All';
            if ($CatSlug){

                $cat = $CatModel::where('category_slug',$CatSlug)->get();
                if (count($cat)){
                    $cat = $cat[0];
                    $list = $cat->posts();
                    $title = $cat->category_name;
                    if ($search){
                        $list->where('post_title','like','%'.$search.'%');
                    }
                    $list = $list->paginate(config('settings.pagination.beta'));
//                    dd($list);
                }else{
                    $list = array();
                }

            }else{
                $list = $model::select('*');
                if ($search){
                    $list->where('post_title','like','%'.$search.'%');
                }
                $list = $list->paginate(config('settings.pagination.beta'));
            }

            return array('type'=>'success','data' => $list,'title'=>$title);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }
    }

    public function postPage($slug){

        $post = $this->getPostBySlug($slug);
        $data = array();
        if ($post['type'] == 'error'){
            return $post;
        }else{
            $data['post']= $post['data'];
        }
        $randomPosts = $this->randomPosts('2',$post['data']->id);
        $recentPosts = $this->recentPosts('4',$post['data']->id);
        $categories = $this->category_list();

        $data['categories'] = $categories['data'];
        $data['randomPosts'] = $randomPosts;
        $data['recentPosts'] = $recentPosts;
        $data['type'] = 'success';

        return $data;

    }

    public function getPostBySlug($slug){
        try{
            $model = new BlogPost();
            $post = $model::where('post_slug',$slug)->get();
            if (count($post) == 0){
                return array('type'=>'error','data' => array());
            }
            return array('type'=>'success','data' => $post[0]);
        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array());

        }
    }

    public function recentPosts($limit,$except=false){
        $model = new BlogPost();
        $posts = $model::select('*');
        if ($except){
            $posts->where('id','!=',$except);
        }
        $posts = $posts->orderBy('created_at','desc')->limit($limit)->get();

        return $posts;
    }

    public function randomPosts($limit,$except=false){
        $model = new BlogPost();
        $posts = $model::select('*');
        if ($except){
            $posts->where('id','!=',$except);
        }
        $posts = $posts->inRandomOrder()->limit($limit)->get();

        return $posts;
    }


    public function postSave($request){
//        try{
            $model = new BlogPost();
            $exceptRequest = array(
                '_method','_token','id','featured_img','post_url','tableOfContent'
            );

        $request = $this->manageAttachments($request);

        $request = $this->manageTableOfContent($request);
        $verifySlug = $this->verifySlug($request);

        if ($verifySlug['type'] == 'error'){
                return array('type'=>'error','msg' => 'Post Slug Already exist','data'=>$request);
            }else{
                $request = $verifySlug['request'];
            }

            if (isset($request->id)){

                $post =  $model::where('id',$request->id)->update($request->except($exceptRequest));
            }else{

                $post =  $model::create($request->except($exceptRequest));
            }

            return array('type'=>'success','msg' => 'Post successfully saved');
//        }catch (\Exception $exception){
//            $helper = new Helper();
//            $helper::errorHandling($exception);
//            return array('type'=>'error','msg' => 'Something went wrong, Please try again later','data'=>$request);
//
//        }
    }

    private function manageTableOfContent($request){
        if (isset($request->tableOfContent)){
            $table = array('tableOfContent'=>$request->tableOfContent);
            $table = json_encode($table);
            $request->request->add(['extra' =>$table]);
            return $request;
        }
        return false;
    }

    private function verifySlug($request){
        $model = new BlogPost();
        $url = strtolower($request->post_url);
        $url = str_replace(' ','-',$url);
        if (isset($request->id)){
            $verify = $model::where('post_slug',$url)->where('id','!=',$request->id)->count();
        }else{
            $verify = $model::where('post_slug',$url)->count();
        }
        if ($verify == '0'){
            $request->request->add(['post_slug' =>$url]);
            return ['type'=>'verified','request'=>$request];
        }else{
            return ['type'=>'error','request'=>$request];
        }


    }
    public function manageAttachments($request){

        if ($file = $request->file('featured_img')) {

            $memeType = $file->getMimeType();
            $path = "uploads/posts";
            if(!is_dir($path)){
                mkdir($path, 0777, true);
            }
            $name = rand(0,9999).$file->getClientOriginalName();
            $file->move($path, $name);
            $request->request->add(['post_img' => $path.'/'.$name]);

        }
        return $request;
    }

    public function getPost($id){
        try{
            $model = new BlogPost();
            $post = $model::find($id);
            if ($post){
                return array('type'=>'success','data' => $post);
            }else{
                abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

    public function PostDelete($id){
        try{
            $model = new BlogPost();
            $post = $model::find($id);

            if (file_exists($post->post_img)) {
                unlink($post->post_img);
            }

            if ($post){
                $post->delete();
                return array('type'=>'success','msg' => 'Post successfully deleted');
            }else{
                abort('404');exit();
            }

        }catch (\Exception $exception){
            $helper = new Helper();
            $helper::errorHandling($exception);
            return array('type'=>'error','data' => array(),'msg'=>'Something went wrong, Please try again later.');

        }
    }

   public function getRandomPosts($limit){
       $model = new BlogPost();
       $posts = $model::inRandomOrder()->limit($limit)->get();
       return $posts;
   }



}