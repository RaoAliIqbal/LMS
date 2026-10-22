<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = ['category_name','category_slug','status'];

    public function posts(){
        return $this->hasMany('App\Models\BlogPost','blog_category_id','id');
    }

}
