@extends('layouts.web')

@section('content')

    <section class="page_sidepadding">
        <section class="blog_detail_pg">
            <h2 class="personal_finance_hdng"><a href="#">{{ isset($post->category)?$post->category->category_name:'Uncategorized' }}</a></h2>
            <div class="row">
                <div class="col-md-8">
                    <div class="blog_left_sect">
                        <div class="blog_left_dtl_img">
                            <img src="{{ ($post->post_img != null)?url($post->post_img):config('settings.post_placeholder') }}" alt="">

                            <h1>{{ $post->post_title }}</h1>
                        </div>
                        @if(isset(json_decode($post->extra)->tableOfContent))
                        <div class="table_contents_div">
                            <h2>Table of contents </h2>
                            <div class="table_contents_list">
                                <ul>
                                    @foreach(json_decode($post->extra)->tableOfContent as $val)
                                        <li>{{ $val }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                       {!! $post->post !!}

                        @if(count($randomPosts) == 2)
                        <div class="Read_next_blog_div d-none d-lg-block">
                            <h2>Read Next</h2>
                            <ul>
                                @foreach($randomPosts as $randomPost)
                                <li>
                                    <a href="{{ route('blogDetails',$randomPost->post_slug) }}">
                                        <img src="{{ ($randomPost->post_img != null)?url($randomPost->post_img):config('settings.post_placeholder') }}" alt="">
                                        <h3>{{ $randomPost->post_title }}</h3>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                    </div>
                </div>
                <div class="col-md-4">
                    @if(count($recentPosts))
                    <div class="blog_right_sect">
                        <div class="recent_blogs">
                            <h3>Recent Blogs</h3>
                            <ul>
                                @foreach($recentPosts as $recentPost)
                                <li>
                                    <img src="{{ ($recentPost->post_img != null)?url($recentPost->post_img):config('settings.post_placeholder') }}" alt="">
                                    <div class="recent_blogs_des">
                                        <a href="{{ route('blogDetails',$recentPost->post_slug) }}">{{ $recentPost->post_title }}</a>
                                        <span>{{ isset($randomPost->category)?$randomPost->category->category_name:'Uncategorized' }}</span>
                                    </div>
                                </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    @endif
                    @if(count($categories))
                    <div class="blog_right_sect">
                        <div class="recent_blogs">
                            <h3>Categories</h3>
                            <ul>
                                @foreach($categories as $category)
                                <li>

                                    <div class="recent_blogs_des">
                                        <a href="{{ route('blogCategory', $category->category_slug) }}">{{ $category->category_name }}</a>
                                    </div>
                                </li>
                                @endforeach


                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </section>


@endsection