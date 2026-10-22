@extends('layouts.web')

@section('title', $course->title)

@section('content')
<style>
    .courseDetailsPage {
        margin-top: 60px;
        margin-bottom: 60px;
    }
</style>

<section class="states_section courseDetailsPage">
    <div class="container">
        <div class="shadow_box add_spaces">

            <div class="row">
                <div class="col-sm-12">
                    <div class="small_section_titles">
                        <h4>{{ $course->title }}</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="course_details">
                        
                        {!! $course->description !!}
                        
                        <hr>

                        <h3 class="currency-price" data-usd="{{ Helper::usdDataAmount($course->price) }}">
                            Price: <span class="curr-symbol">$</span><span class="curr-amount">{{ Helper::formatUsd($course->price) }}</span>
                        </h3>

                        <button 
                            type="button"
                            data-type="add-to-cart"
                            data-course-title="{{ e($course->title) }}"
                            data-course-id="{{ $course->id }}"
                            class="altBtn course_small_btn btnLoading addToCartBtn"
                            id="load{{ $course->id }}"
                            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait">
                            Add To Cart
                        </button>

                        <hr>

                        {{-- <a href="{{ route('client_CourseEnroll', $course->id) }}" class="course_details_btn">Enroll Now</a> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</section>

@endsection