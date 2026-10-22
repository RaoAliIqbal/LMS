@extends('layouts.web')

@section('content')
    <section class="page_sidepadding">
        <section class="Investor_education_pg">
            <h1>Courses</h1>
            <div class="row searchRow">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="page search login_form">


                        <form method="GET" action="{{ route('investorEducation') }}">
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
                @if(count($lessons))
                <ul>
                    @foreach($lessons as $lesson)

                    <li class="col-12 col-md-6 col-lg-4">
                        <div class="Investor_education_list">
                            <a href="course.html#1_0"><img src="{{ ($lesson->image != null)?$lesson->image:config('settings.post_placeholder') }}" alt=""></a>
                            <h2><a href="#">{{ $lesson->title }}</a></h2>
                            <a class="learn_more_btn" href="{{ route('educationDetails',$lesson->id) }}">Learn More</a>
                        </div>
                    </li>
                    @endforeach

                </ul>
                    @else
                    <div class="col-md-12 text-center">No Post Found</div>
                @endif
            </div>
        </section>
    </section>
@endsection
