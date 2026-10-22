@extends('layouts.web')

@if($category->id == null)
    @section('title','Enhance Skills | Professional Online Education Courses')
    @section('keywords','Professional Online Education Courses')
    @section('description','Take your chiropractic practice to the next level with our professional online education courses. So, enroll today and enhance your chiropractic skills!')
@else

    @section('title',$category->meta_title)
    @section('keywords',$category->meta_keyword)
    @section('description',$category->meta_description)
@endif

@section('content')
    <section class="top_section_banner" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>Courses</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="category_section blue_bg_section section_tb_pads">

        <div class="container">

            <div class="row row_btm_space">
                <div class="col-sm-12">
                    <div class="small_section_titles white_txt inc_size_title">
                        <h4>Courses</h4>
                    </div>
                </div>
            </div>

            <div class="container">

                @if(isset($request))
                <div class="row row_btm_space white_block">
                    <div class="col-sm-12">


                        <form action="{{ route('coursesPage') }}" method="GET" class="custom_form_style">
                            <input type="hidden" name="searched" value="true">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    	<select class="form-control" name="category_id" is="category_name">
                                    	    <option value="" selected>Select Category</option>
                                            @foreach($categories as $category)
                                                <option {{ (request('category_id') == $category->id)?'selected':'' }} value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    	<select required class="form-control" name="state_id" is="category_name">
                                    	    <option value="" selected>Select State</option>
                                            @foreach($states as $state)
                                                <option {{ (request('state_id') == $state->id)?'selected':'' }} value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <input type="text" value="{{ request('search') }}" class="form-control" name="search" id="seacrch_text" placeholder="Search here...">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                      <button type="submit" class="btn btn-default change_btn_style">Search</button>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <a href="{{ route('coursesPage') }}" class="btn altBtn ">Reset</a>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div id="CartAlert"></div>
                    </div>
                </div>

                @if(count($regulations))
                    @foreach($regulations as $regulation)
                            <div id="regulation">
                                <h4>{{ $regulation->title }}</h4>
                                {!! $regulation->description !!}
                                 <p><b>STATE BOARD LINK: </b> <a href="{!! $regulation->link !!}">{!! $regulation->link !!} </a></p>
                            </div>
                    @endforeach
                @endif



                <div class="row coursesCategoryTabs">
{{--                    <div class="col-sm-12">--}}
                         @if(count($courses))
                                    @foreach($courses as $course)
                                        @include('web.partials.course_item')
                                    @endforeach
                             @else
                             @if(request()->has('searched') || request()->has('search'))
                             <p style="color: #fff">No Course Found</p>
                            @endif
                         @endif
{{--                    </div>--}}
                </div>
            </div>

        </div>

    </section>
    <style>
        .coursebox {
            background-color: #0000004d;
            min-height: 450px;
        }
        .course_small_content h3 {
            font-size: 15px !important;
        }

        ul.small_btns_cour {
            justify-content: flex-start;
            flex-direction: column;
        }

        ul.small_btns_cour li {
            width: 100% !important;
        }
        .altBtn.course_small_btn.btnLoading {
            width: 100% !important;
            margin-bottom: 10px;
            background: #6ba546;
        }

        ul.small_btns_cour li a {
            width: 100%;
            text-align: center;
        }

        .section_tb_pads{ padding: 1em 0px !important; }
    </style>
@endsection
