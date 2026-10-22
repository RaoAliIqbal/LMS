<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Blogs Routes
|--------------------------------------------------------------------------
*/

Route::get('/blog', 'WebsiteController@blog')->name('blog');
Route::get('/blog-category/{catSlug}', 'WebsiteController@blog')->name('blogCategory');
Route::get('/blog-read/{slug}', 'WebsiteController@blogDetails')->name('blogDetails');



//Admin Routes
Route::group(['prefix' => 'admin','middleware'=>['verified','admin','auth']], function () {
    //Blog Cat
    Route::get('/blog-categories', 'BlogController@categoriesLists')->name('admin_blog_categories');
    Route::get('/blog-categories/add', 'BlogController@categoriesAdd')->name('admin_add_cat');
    Route::get('/blog-categories/{id}/edit', 'BlogController@categoriesEdit')->name('admin_edit_cat');
    Route::get('/blog-categories/{id}/delete', 'BlogController@categoriesDelete')->name('admin_delete_cat');
    Route::post('/blog-categories/save', 'BlogController@categoriesSave');
    // Blog Post
    Route::get('/blog-posts', 'BlogController@postLists')->name('admin_blog_posts');
    Route::get('/blog-posts/add', 'BlogController@postAdd')->name('admin_add_post');
    Route::post('/blog-post/save', 'BlogController@postSave');
    Route::get('/blog-post/{id}/edit', 'BlogController@postEdit')->name('admin_edit_post');
    Route::get('/blog-post/{id}/delete', 'BlogController@postDelete')->name('admin_delete_post');
});
