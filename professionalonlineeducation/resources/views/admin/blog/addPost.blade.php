@extends('admin.layouts.app')

@section('title')
{{ $pageData['name'] }}
@endsection
@section('content')
    <div class="content-main " id="content-main">
        <!-- ############ Main START-->
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.partials.alertBox')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">



                        <div class="box">
                            <div class="box-header">
                                <h2>{{ $pageData['name']  }}</h2>
                            </div>
                            {!! Form::open(['method'=>'POST','class'=>'validate','files'=>true,'action'=>['BlogController@postSave']]) !!}
                           @if($pageData['type'] == 'edit')
                                <input type="hidden" value="{{ $post->id }}" name="id">
                            @endif
                            <div class="box-body profileView">
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Post Title*</label>
                                        {{ Helper::input('post_title','Category Name',isset($post->post_title)?$post->post_title:'',true,['id'=>'post_title'],null) }}
                                    </div>
                                </div>
                                {{--@if($pageData['type'] == 'add')--}}
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Post Slug*</label>
                                        {{ Helper::input('post_url','Post Slug',isset($post->post_slug)?$post->post_slug:'',true,['id'=>'post_slug'],null) }}
                                    </div>
                                </div>
                                {{--@endif--}}
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Post Title*</label>
                                        <select class="form-control" name="blog_category_id" required id="">
                                            <option selected disabled value="">Select Post Category</option>
                                            @foreach($categories as $category)
                                                <option {{ (isset($post->blog_category_id)?($post->blog_category_id == $category->id)?'selected':'':'') }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Post*</label>
                                        <textarea name="post" class="html">{{ isset($post->post)?$post->post:'' }}</textarea>
                                    </div>
                                </div>



                                    <div class="form-row">
                                        <div class="form-group col-sm-12 tableOfContentCon">
                                            <label>Table Of Content </label>
                                            @if($pageData['type'] == 'edit' && isset(json_decode($post->extra)->tableOfContent))

                                                @foreach(json_decode($post->extra)->tableOfContent as $val)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input  type="text" value="{{ $val }}" name="tableOfContent[]" class="form-control">
                                                </div>
                                                @if($loop->index == 0)
                                                <div class="col-md-2"><span class="addMore">+</span></div>
                                                    @else
                                                <div class="col-md-2"><span onclick="removeOpt(this)" class="removeOpt">-</span></div>
                                                @endif
                                            </div>
                                                @endforeach

                                            @else
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input  type="text"  name="tableOfContent[]" class="form-control">
                                                    </div>

                                                    <div class="col-md-2"><span class="addMore">+</span></div>

                                                </div>

                                        </div>
                                    </div>


                                       @endif
                                <div class="added"></div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Excerpt Text*</label>
                                        <textarea name="post_excerpt" required class="form-control">{{ isset($post->post_excerpt)?$post->post_excerpt:'' }}</textarea>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Featured Image</label>
                                        <input type="file"  class="form-control" name="featured_img">
                                    </div>
                                </div>
                                @if(isset($post->post_img))

                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <img src="{{ url($post->post_img) }}" width="150" alt="">
                                    </div>
                                </div>
                                @endif


                                <br>
                                <br>
                                <h5>FOR SEO</h5>
                                <hr>

                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>SEO Title</label>
                                        {{ Helper::input('seo_title','SEO Title',isset($post->seo_title)?$post->seo_title:'',false,['id'=>'post_title'],null) }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>SEO Meta</label>
                                        <textarea name="seo_meta" class="form-control">{{ isset($post->seo_meta)?$post->seo_meta:'' }}</textarea>
                                    </div>
                                </div>



                                <div class="text-right">
                                    {{ Form::submit('Save',['class'=>'btn btn-primary cstm_btn']) }}

                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                </div>

            </div>

            <br>


        </div>
        <!-- ############ Main END-->
    </div>
    <div id="tableContent" style="display: none">
        <div class="row">
            <div class="col-md-6">
                <input type="text"  name="tableOfContent[]" class="form-control">
            </div>
            <div class="col-md-2"><span onclick="removeOpt(this)" class="removeOpt">-</span></div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(".addMore").click(function () {
//            added
            var html = $("#tableContent").html();
            $(".added").append(html);
        });
        function removeOpt(t) {
            ele = $(t);
            ele.closest('.row').remove();
        }
    </script>
@endpush
