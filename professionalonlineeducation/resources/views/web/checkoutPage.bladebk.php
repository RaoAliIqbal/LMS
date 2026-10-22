@extends('layouts.web')

@section('content')
    <section class="top_section_banner" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>Checkout</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="category_section blue_bg_section section_tb_pads">

        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div class="small_section_titles white_txt inc_size_title">
                        <h4>Checkout</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout_section">

            <div class="container">
                <div class="row">
                    <div class="block_title add_some_new_space" data-aos="fade-right">
                        <h2>Your Courses</h2>
                        @include('layouts.partials.alertBox')
                    </div>
                    <div class="col-md-8">
                        @if(count($courses))

                                @foreach($courses as $course)

{{--                                    <li> <br> ${{ $course->price }}</li>--}}



                                      <div class="row">
                                          <div class="white_bg_block">
                                          <div class="col-sm-3">
                                              <div class="course_img">
                                                  <img src="{{ url($course->image) }}"/>
                                              </div>
                                          </div>
                                          <div class="col-sm-9">
                                              <div class="course_item_detail">
                                                  <h3>{{ $course->title }}</h3>
                                                  <p class="offer">${{ $course->price }}</p>
                                              </div>
                                          </div>
                                          </div>
                                      </div>


                                @endforeach
                                @else
                                        <p>Your Cart is empty</p>
                                @endif





                    </div>
                    <div class="col-md-4">
                        <form action="{{ route('cartEnroll') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">Your Age</label>
                                <input type="text" class="form-control" required name="age" {{ auth()->user()->age }}>
                            </div>
                            <div class="form-group">
                                <label for="">Your State</label>
                                <select name="state_id" class="form-control">
                                    @foreach($states as $state)
                                        <option {{ ($state->name == auth()->user()->state)?'selected':'' }} value="{{ $state->name }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="submit" class="btn btn-primary btn-lg" value="Submit" />
                        </form>
{{--                        <a href="{{ route('cartEnroll') }}" class="btn btn-primary btn-lg">Submit</a>--}}
                    </div>
                </div>
            </div>

    </section>


    <style>
        .coursebox {
            background-color: #0000004d;
            min-height: 450px;
        }
        .cart_block {
            display: none;
        }
    </style>
@endsection
