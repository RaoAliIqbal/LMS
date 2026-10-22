{{--<div class="row courseItem">--}}
{{--    <div class="col-md-6">--}}
{{--        <a href="{{ route('courseDetailsPage',$course->id) }}">--}}
{{--            <img src="{{ url($course->image) }}"/>--}}
{{--        </a>--}}
{{--    </div>--}}
{{--    <div class="col-md-6">--}}
{{--        <h3>{{ $course->title }}</h3>--}}
{{--        <button type="button" data-type="add-to-cart" data-courseId="{{ $course->id }}" class="altBtn course_small_btn btnLoading" id="load1"--}}
{{--                data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait">Add To Cart</button>--}}
{{--        <a href="{{ route('courseDetailsPage',$course->id) }}" class="course_small_btn">More Info</a>--}}
{{--        <p class="offer"><span>Save a further 10% off</span> at checkout when you enter coupon code AC10.</p>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="col-sm-4">
    <div class="courseItem_new">


        <a href="{{ route('courseDetailsPage',$course->id) }}">
            <img src="{{ url($course->image) }}" width="100%"/>
        </a>
        <div class="course_small_content">
            
            
        @if($course->tag == 1) <span class="tag">Most Popular</span> @endif
        @if($course->tag == 2) <span class="tag">New Course</span> @endif
        @if($course->tag == 3) <span class="tag"> FREE </span> @endif
        @if($course->tag == 4) <span class="tag"> Partial Course </span> @endif
        @if($course->tag == 5) <span class="tag"> NATIONAL </span> @endif
        @if($course->tag == 6) <span class="tag"> AZBCE 18-CAN-001 </span> @endif
        @if($course->tag == 7) <span class="tag"> AZBCE 18-CAN-002 </span> @endif
        @if($course->tag == 8) <span class="tag"> AZBCE 18-CAN-003 </span> @endif
        @if($course->tag == 9) <span class="tag"> AZBCE 18-CAN-004 </span> @endif
        @if($course->tag == 10) <span class="tag"> AZBCE 18-CAN-005 </span> @endif
        @if($course->tag == 11) <span class="tag"> AZBCE 18-CAN-006 </span> @endif
        @if($course->tag == 12) <span class="tag"> AZBCE 18-CAN-007 </span> @endif
        @if($course->tag == 13) <span class="tag"> AZBCE 18-CAN-008 </span> @endif
        @if($course->tag == 14) <span class="tag"> AZBCE 18-CAN-009 </span> @endif
        @if($course->tag == 15) <span class="tag"> AZBCE 18-CAN-010 </span> @endif
        @if($course->tag == 16) <span class="tag"> PACE Approved </span> @endif

            
            <h3>{{ $course->title_two }}</h3>
            <h6>{{ $course->hours }} {{ Helper::courseHrTag($course) }}</h6>
            <h6 class="currency-price" data-usd="{{ Helper::usdDataAmount($course->price) }}"><span class="curr-symbol">$</span><span class="curr-amount">{{ Helper::formatUsd($course->price) }}</span></h6>
            <ul class="small_btns_cour">
                <li><button type="button" data-type="add-to-cart"
                            data-course-title="{{ e($course->title) }}"
                            data-course-id="{{ $course->id }}" class="altBtn course_small_btn btnLoading" id="load1"
                            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait">Add To Cart</button></li>
                <li><a href="{{ route('courseDetailsPage',$course->id) }}" class="course_small_btn">More Info</a></li>
            </ul>
{{--            <p class="offer"><span>Save a further 10% off</span> at checkout when you enter coupon code AC10.</p>--}}
        </div>
    </div>
</div>
