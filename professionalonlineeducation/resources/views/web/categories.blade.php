@extends('layouts.web')

@section('content')
    <section class="top_section_banner" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>{{ $state->name }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="states_section blue_bg_section section_tb_pads">

        <div class="container">

            <div class="row row_btm_space">
                <div class="col-sm-12">
                    <div class="small_section_titles white_txt inc_size_title">
                        <h4>Choose a Category</h4>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="states_list">
{{--                        <ul class="course_cat_list">--}}
                          @if(count($categories))
                              @php $i = 1; @endphp
                                @foreach($categories as $category)
                                    <li><a href="{{ route('coursesByStateAndCategory',['slug'=>strtolower($state->slug),'categorySlug'=>$category->slug]) }}">{{ $category->title }}</a></li>
{{--                                    <li class="CatLi">--}}

{{--                                        <div class="cat_course_block add_btm_space {{ ($i % 3 == 0) ?'set_img_cat_height':'' }}">--}}
{{--                                            <a href="{{ route('coursesByStateAndCategory',['slug'=>strtolower($state->slug),'id'=>$category->id]) }}">--}}
{{--                                                <img src="{{ url('webAssets/images/course-1.png') }}"/>--}}
{{--                                                <div class="cat_titles center_cat_txt">--}}
{{--                                                    <h4>{{ $category->title }}</h4>--}}
{{--                                                </div>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}


{{--                                      </li>--}}



                                        @php $i++; @endphp
                                @endforeach
                          @endif
{{--                        </ul>--}}
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </section>


@endsection
