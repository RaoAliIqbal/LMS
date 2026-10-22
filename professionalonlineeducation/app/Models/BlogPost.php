<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = ['post_title','blog_category_id','extra','post','post_excerpt','post_img','seo_title','seo_meta','post_slug','status'];

    public function category(){
        return $this->belongsTo('App\Models\BlogCategory','blog_category_id','id');
    }



}
