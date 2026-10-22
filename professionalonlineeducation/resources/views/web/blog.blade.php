@extends('layouts.web')

@section('content')

    <section class="page_sidepadding">
        <section class="blog_bnr">
            <div class="blog_bnr_row">
                <h1>Blogs & Articles</h1>
                <h2><strong>{{ $title }}</strong></h2>
            </div>
        </section>
        <section class="blog_listing">
            <div class="row searchRow">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="page search login_form">


                        <form method="GET" action="{{ route('blog') }}">
                            <div class="row">

                                <ul>
                                    <li class="col-lg-12">
                                        <div class="form_input_field form_input_field01 searchInput">
                                            <input type="text" value="{{ (isset($request->search)?$request->search:'') }}" name="search"  required  id="email" placeholder="Type Here...">
                                            <label for="email">Search</label>
                                        </div>
                                    </li>

                                    <li class="col-lg-12">
                                        <input type="submit"  value="Search">
                                    </li>
                                </ul>
                            </div>
                        </form>


                    </div>
                </div>
                <div class="col-md-3"></div>

            </div>
            <div class="row">
                @if(count($posts))
                <ul>
                    @foreach($posts as $post)
                    <li class="col-12 col-md6 col-lg-4">
                        <div class="blog_list_row">
                            <div class="blog_list_row_img">
                                <a href="#"><img src="{{ ($post->post_img != null)?$post->post_img:config('settings.post_placeholder') }}" alt=""></a>
                                <span class="blog_tag"><a href="#">{{ (isset($post->category))?$post->category->category_name:'uncategorized' }} </a></span>
                            </div>
                            <div class="blog_list_row_des">
                                <h2><a href="{{ route('blogDetails',$post->post_slug) }}">{{ $post->post_title }} </a></h2>
                                <p>{{ $post->post_excerpt }}</p>
                                <a class="blog_learn_more_btn" href="{{ route('blogDetails',$post->post_slug) }}">Learn More</a>
                            </div>
                        </div>
                    </li>
                    @endforeach


                </ul>
                    @else
                        <div class="col-md-12 text-center">No Post Found</div>
                    @endif
                    {{ $posts->appends(request()->all())->links()  }}
            </div>
        </section>
    </section>

@endsection