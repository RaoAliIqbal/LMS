@extends('layouts.web')

@section('content')
    <div class="container">
        <section class="user_dashboard">
            <div class="row">
                @include('layouts.partials.dashboardNav')
                <div class="col-lg-10">
            <div class="user_info_block">
                <div class="container">
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="customer_profile_img small_space_btm">
                                <div class="profileImg"><img
                                        src="{{ $users->img == null ? 'https://professionalonlineeducation.com/webAssets/images/user-icon-placeholder-1.png' : url($users->img) }}"
                                        class="userPlaceholder" /></div>
                                <!--<span class="firts_last_name_letters">UP</span>-->
                            </div>
                            <form method="post" enctype="multipart/form-data" id="profileForm"
                                action="{{ route('updateProfileImg') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="file" onChange="profileChange()" name="profileImg" id="profileImg"
                                            class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-sm-6">
                            <div class="user_acc_info">
                                <h2>{{ $users->first_name != '' ? $users->FullName : $users->name }}</h2>
                                <div class="small_info">
                                    <p><span class="attr_name">Student ID:</span> {{ $users->id * 500 }}</p>
                                    <p><span class="attr_name">States:</span> {{ $users->state }}</p>
                                    @if (count($professions))
                                        <p><span class="attr_name">Profession:</span>
                                            @foreach ($professions as $profession)
                                                {{ $profession->title }} @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </p>
                                    @endif
                                    <p><span class="attr_name">Member Since:</span>
                                        {{ \Carbon\Carbon::parse($users->created_at)->format('M d, Y') }}</p>
                                </div>

                                <div class="state_licence_block">
                                    <h2>State Licenses</h2>
                                    @if (count($userLinceses))
                                        @foreach ($userLinceses as $lincese)
                                            <ul class="u_info licenseList">
                                                <li>License #: <span class="licence_value">{{ $lincese->licence_no }}</span>
                                                </li>
                                                <li>Renewal: <span
                                                        class="licence_value">{{ $lincese->renewal_data ? \Carbon\Carbon::parse($lincese->renewal_data)->format('M d, Y') : '—' }}</span>
                                                </li>
                                            </ul>
                                        @endforeach
                                    @else
                                        No license Found
                                    @endif
                                    <a href="{{ route('client_licenses') }}" class="u_update_btn">Manage your state
                                        licenses</a>
                                </div>



                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="user_contact_info">
                                <ul class="u_info">
                                    <li><a href="mailto:bairdbairdn@gmail.com">{{ $users->email }}</a> </li>
                                    <li><a href="tel:(602)775-6764">{{ $users->phone }}</a></li>
                                </ul>

                                <a href="" class="u_update_btn" data-toggle="modal" data-target="#myModal">Update
                                    Your Account Details</a>
                                <a href="" class="u_update_btn" data-toggle="modal"
                                    data-target="#ClinicDetails">Update Clinic Details</a>

                                <a href="{{ route('logout') }}" class="course_small_btn">Logout</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="payment_history_block">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Payment History</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            @include('client.partials.paymentHistoryTable', ['paymentLogs' => $paymentLogs ?? []])
                            <a href="{{ route('paymentHistory') }}" class="u_update_btn">VIEW PAYMENT HISTORY</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="u_courses_info">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9">
                            <h2>Online Courses</h2>
                            <div class="block_details">
                                @if (count($courses))
                                    @foreach ($courses as $course)
                                        @php $c = $course->courseWithTrashed ?? $course->course; @endphp
                                        @if ($c)
                                            <div class="row small_space_btm">
                                                <div class="col-sm-8">

                                                    <div class="course_info">
                                                        <h3><a
                                                                href="{{ route('client_Course', ['id' => $c->id, 'year' => \Carbon\Carbon::parse($course->created_at)->year]) }}">{{ $c->title }}</a>
                                                        </h3>
                                                        <ul class="cour_small_details">
                                                            <li>Start date
                                                                <span>{{ \Carbon\Carbon::parse($course->enrolled_at)->format('M d, Y') }}</span>
                                                            </li>
                                                            @if ($course->completed_at != null)
                                                                <li>Completion Date
                                                                    <span>{{ \Carbon\Carbon::parse($course->completed_at)->format('M d, Y') }}</span>
                                                                </li>
                                                            @endif
                                                            <li>Credit Hours<span>{{ $c->hours }}</span> </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 make_right_btn">

                                                    @if ($course->completed_at != null)
                                                        @if (!empty($c->categories[0]) && in_array($c->categories[0]->category_id, config('settings.CeCategories', [])))
                                                            <a href="{{ route('courseEvaluation', ['id'=>$course->lesson_id, 'year'=>date('Y', strtotime($course->created_at))]) }}"
                                                                class="u_update_btn">Download certificate</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="{{ route('client_Courses') }}" class="u_update_btn">VIEW ALL YOUR
                                            COURSES</a>
                                    </div>
                                    <div class="col-sm-6 make_right_btn">
                                        <a href="{{ route('coursesPage') }}" class="u_update_btn">BROWSE ALL COURSES</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <h2>Live Events</h2>
                            <div class="block_details">
                                @if (count($users->seminars))
                                    @php $seminars = $users->seminars()->limit(3)->get(); @endphp
                                    <ul class="liveSeminarDashboardUl">
                                        @foreach ($seminars as $seminar)
                                            <li><a
                                                    href="{{ route('client_event', $seminar->id) }}">{{ $seminar->course_title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No Events found.</p>
                                @endif
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="{{ route('client_events') }}" class="u_update_btn">VIEW ALL</a>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>


                </div>
            </div>
        </section>

    </div>


    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                {{--            <form action=""></form> --}}
                {!! Form::open([
                    'method' => 'POST',
                    'id' => 'formOne',
                    'class' => 'validate custom_form_style',
                    'files' => true,
                    'action' => ['Client\ProfileController@updateProfile'],
                ]) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Details</h4>
                </div>
                <div class="modal-body">
                    <ul>
                        <li class="col-md-4">
                            <div class="form-group">
                                <label for="fullname">First name</label>
                                <input type="text" class="form-control" name="first_name"
                                    value="{{ auth()->user()->first_name }}" id="fullname" placeholder="First name">
                            </div>
                        </li>
                        <li class="col-md-4">
                            <div class="form-group">
                                <label for="fullname">Middle name</label>
                                <input type="text" class="form-control" name="middle_name"
                                    value="{{ auth()->user()->middle_name }}" id="fullname" placeholder="Middle name">
                            </div>
                        </li>
                        <li class="col-md-4">
                            <div class="form-group">
                                <label for="fullname">Last name</label>
                                <input type="text" class="form-control" name="last_name"
                                    value="{{ auth()->user()->last_name }}" id="fullname" placeholder="Last name">
                            </div>
                        </li>
                        <li class="col-md-12">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="text" class="form-control" name="email"
                                    value="{{ auth()->user()->email }}" disabled readonly id="email"
                                    placeholder="email">
                            </div>
                        </li>
                        <li class="col-md-12">
                            <div class="form-group">
                                <label for="fullname">Address</label>
                                <textarea class="form-control" name="address">{{ auth()->user() ? auth()->user()->address : '' }}</textarea>
                            </div>
                        </li>

                        <li class="col-md-4">
                            <div class="form-group">
                                <label for="fullname">City</label>
                                <input type="text" class="form-control" name="city"
                                    value="{{ auth()->user()->city }}" id="fullname" placeholder="City">
                            </div>
                        </li>
                        <li class="col-md-4">
                            <div class="form-group">
                                <label for="email">State</label>
                                <select name="state" class="form-control">
                                    <option disabled selected>Select State</option>
                                    @foreach ($states as $state)
                                        <option {{ $state->name == $users->state ? 'selected' : '' }}
                                            value="{{ $state->name }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </li>
                        <li class="col-md-4">
                            <div class="form-group">
                                <label for="fullname">Zipcode</label>
                                <input type="number" class="form-control" name="zipcode"
                                    value="{{ auth()->user()->zipcode }}" id="fullname" placeholder="Zipcode">
                            </div>
                        </li>


                        <li class="col-md-6">
                            <div class="form-group">
                                <label for="oldpassword">Old Password</label>
                                <input type="password" class="form-control" name="oldpassword" id="oldpassword"
                                    placeholder="oldpassword">
                            </div>
                        </li>
                        <li class="col-md-6">
                            <div class="form-group">
                                <label for="newpassword">New Password</label>
                                <input type="password" class="form-control" name="newpassword" id="newpassword"
                                    placeholder="newpassword">
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="formOne" class="btn btn-primary">Save & Close</button>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
    <div id="ClinicDetails" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">


                <form action="{{ route('saveClinic') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Clinic Details</h4>
                    </div>
                    <div class="modal-body">
                        <ul>
                            <li class="col-md-6">
                                <div class="form-group">
                                    <label for="fullname">Clinic Name</label>
                                    <input type="text" class="form-control" name="clinic_name"
                                        value="{{ auth()->user()->clinic ? auth()->user()->clinic->clinic_name : '' }}"
                                        id="fullname" placeholder="Clinic name">
                                </div>
                            </li>
                            <li class="col-md-6">
                                <div class="form-group">
                                    <label for="fullname">Doctor Name</label>
                                    <input type="text" class="form-control" name="doctor_name"
                                        value="{{ auth()->user()->clinic ? auth()->user()->clinic->doctor_name : '' }}"
                                        id="fullname" placeholder="Doctor name">
                                </div>
                            </li>
                            <li class="col-md-6">
                                <div class="form-group">
                                    <label for="fullname">Phone</label>
                                    <input type="text" class="form-control" name="office_phone"
                                        value="{{ auth()->user()->clinic ? auth()->user()->clinic->office_phone : '' }}"
                                        id="fullname" placeholder="Phone">
                                </div>
                            </li>
                            <li class="col-md-6">
                                <div class="form-group">
                                    <label for="fullname">Address</label>
                                    <textarea class="form-control" name="address">{{ auth()->user()->clinic ? auth()->user()->clinic->address : '' }}</textarea>
                                </div>
                            </li>

                            <li class="col-md-6">
                                <div class="form-group">
                                    <label for="fullname">City</label>
                                    <input type="text" class="form-control" name="city"
                                        value="{{ auth()->user()->clinic ? auth()->user()->clinic->city : '' }}"
                                        id="fullname" placeholder="City">
                                </div>
                            </li>
                            <li class="col-md-6">
                                <div class="form-group">
                                    <label for="email">State</label>
                                    <select name="state" class="form-control">
                                        <option disabled selected>Select State</option>
                                        @foreach ($states as $state)
                                            <option
                                                {{ auth()->user()->clinic ? ($state->name == auth()->user()->clinic->state ? 'selected' : '') : '' }}
                                                value="{{ $state->name }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </li>
                            <li class="col-md-6">
                                <div class="form-group">
                                    <label for="fullname">Zipcode</label>
                                    <input type="number" class="form-control" name="zipcode"
                                        value="{{ auth()->user()->clinic ? auth()->user()->clinic->zipcode : '' }}"
                                        id="fullname" placeholder="Zipcode">
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="formOne" class="btn btn-primary">Save & Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="couponmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <img src="{{ url('webAssets/images/close-icon.png') }}" alt="">
            </button>
            <div class="modal-content">

                <div class="modal-body">
                    <div id="newcountdown"></div>
                    <h3>Congratulations you are qualified for 20% Off on your course enrollment for next 24 hours </h3>
                    {{--                <p>Register for any of our courses in the next 24 hours and get an exclusive 20% discount on your course. </p> --}}
                    <a href="javascript:;" id="avail" class="btn coupon-btn">Save 20% Now</a>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <style>
        a.u_update_btn {
            background: #6ea947;
            color: #fff;
            padding: 6px 15px;
            border-radius: 15px;
            font-size: 16px;
        }

        a.u_update_btn:hover {
            background: #487629;
        }

        ul.u_info.licenseList {
            width: 45%;
            float: left;
        }

        ul.liveSeminarDashboardUl li {
            color: #0f75bc;
            font-size: 16px;
            margin-bottom: 25px;
            cursor: pointer;
        }
    </style>
    <script>
        function profileChange() {
            $("#profileForm").submit();
        }
    </script>
    @php
        $userCreatedAt = \Carbon\Carbon::parse($users->created_at);
        $now = \Carbon\Carbon::now();
    @endphp

    @if ($now->diffInHours($userCreatedAt) <= 24 && count($courses) == 0)
        <script>
            let code = 'RLC240';
            $("#avail").click(function() {

                localStorage.setItem('code', code);
                location.assign('/courses');
            });
            $(document).ready(function() {
                localStorage.setItem('code', code);
            });

            CountDownTimer('{{ $userCreatedAt->addDay(1) }}', 'newcountdown');

            function CountDownTimer(dt, id) {
                var end = new Date(dt);

                var _second = 1000;
                var _minute = _second * 60;
                var _hour = _minute * 60;
                var _day = _hour * 24;
                var timer;

                function showRemaining() {
                    var now = new Date();
                    var distance = end - now;
                    if (distance < 0) {

                        clearInterval(timer);
                        document.getElementById(id).innerHTML = 'EXPIRED!';

                        return;
                    }
                    var days = Math.floor(distance / _day);
                    var hours = Math.floor((distance % _day) / _hour);
                    var minutes = Math.floor((distance % _hour) / _minute);
                    var seconds = Math.floor((distance % _minute) / _second);

                    //document.getElementById(id).innerHTML = days + 'days ';
                    document.getElementById(id).innerHTML = '<ul><li><span>' + hours + '</span><strong>Hours</strong></li>';
                    document.getElementById(id).innerHTML += '<li><span>' + minutes + '</span><strong>Minutes</strong></li>';
                    document.getElementById(id).innerHTML += '<li><span>' + seconds +
                        '</span><strong>Seconds</strong></li></ul>';
                }

                timer = setInterval(showRemaining, 1000);
            }

            $(window).on('load', function() {
                // setTimeout(
                //     function()
                //     {
                //         $('#couponmodal').modal('show');
                //     }, 0000);

            });
        </script>
    @endif
@endpush
