@extends('layouts.web')
@section('title','Transform Career | Professional Online Education Enroll')
@section('keywords','Professional Online Education Enroll')
@section('description','Ready to take the next step in your chiropractic career? Enroll in our professional online education enroll program and gain the skills you need to succeed.')
@section('content')
                                    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
<style>
/* Right column: allow scroll but hide scrollbars + stop horizontal bar */
.hn-right-scroll{
  overflow-y: auto;
  overflow-x: hidden;              /* ✅ bottom line (horizontal bar) remove */
  padding-right: 0 !important;     /* ✅ extra width issue fix */
}

/* ✅ Hide scrollbar - Chrome/Edge/Safari */
.hn-right-scroll::-webkit-scrollbar{
  width: 0;
  height: 0;
}

/* ✅ Hide scrollbar - Firefox */
.hn-right-scroll{
  scrollbar-width: none;           /* Firefox */
}

/* ✅ Hide scrollbar - IE/old Edge */
.hn-right-scroll{
  -ms-overflow-style: none;
}
/* Left column sticky */
.hn-left-sticky{
  position: sticky;
  top: 20px;              /* header ho to isko 80px / 100px kar dena */
  align-self: flex-start; /* important */
  height: fit-content;
}

/* Right column only scroll */
.hn-right-scroll{
  max-height: calc(100vh - 40px); /* viewport height minus top/bottom gap */
  overflow-y: auto;
  padding-right: 10px;            /* scrollbar space */
}

/* Optional: scroll smooth */
.hn-right-scroll{
  -webkit-overflow-scrolling: touch;
}

/* Mobile pe normal behavior */
@media (max-width: 767px){
  .hn-left-sticky{
    position: relative;
    top: 0;
  }
  .hn-right-scroll{
    max-height: none;
    overflow: visible;
    padding-right: 0;
  }
}
    .student_data input, .student_data textarea, .student_data select.form-control {
    resize: none;
}
</style>
    <section class="top_section_banner" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>Sign Up</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section  class="login_section" style="padding: 50px 0;">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger my_note_txt" role="alert">
                    <strong>Note:</strong> Each student needs there own individual email in order to Sign Up.
                    </div>
                </div>
            </div>

            <div class="row style_row">
                <div class="col-sm-7 hn-left-sticky">
                    <div class="block_title" data-aos="fade-right">
                        <h2>Sign Up</h2>
                        <p>Welcome to Canham college online chiropractic assistant training company login page! Our comprehensive online programs provide expert education and training to those interested in the chiropractic industry. Our online platform offers a flexible and accessible learning experience, allowing students to study anytime and anywhere, at their own pace. Our expert instructors use interactive methods of teaching to provide a hands-on and engaging learning experience. We offer personalized attention and support to each student to ensure they receive the best possible education. Enroll with us today and take the first step towards becoming a skilled and knowledgeable chiropractic assistant!</p>

                        <a href="{{ route('login') }}" class="btn btn-default">Sign In</a>
                    </div>
                </div>
              <div class="col-sm-5 hn-right-scroll">
                    <form class="form validate" method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">First Name <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('first_name') }}" required name="first_name" class="form-control" id="first_name" placeholder="">
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Middle Initial <span class="label_start"></span></label>
                                    <input type="text" value="{{ old('middle_name') }}" name="middle_name" class="form-control" id="middle_name" placeholder="">
                                    @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Last Name <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('last_name') }}" required name="last_name" class="form-control" id="last_name" placeholder="">
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="office_data">
                            <strong>Office Address <i class="fa-solid fa-arrow-down"></i></strong>

                            {{-- ✅ Added: Use my home address option --}}
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-12">
                                    <div class="form-group" style="margin-bottom:10px;">
                                        <label style="cursor:pointer; font-weight:600;">
                                            <input type="checkbox" id="use_home_address">
                                            Use my home address
                                        </label>
                                        <small style="display:block; opacity:1; color:#fff">(For students who don’t have office addresses)</small>
                                    </div>
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Office Name <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('office_name') }}" required name="office_name" class="form-control">
                                    @error('office_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Office Telephone <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('office_phone') }}" required name="office_phone" class="form-control">
                                    @error('office_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Office Address (please avoid special characters)<span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('office_address') }}" required name="office_address" class="form-control" id="office_address">
                                    @error('office_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Office City <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('city') }}" required name="office_city" class="form-control" id="office_city">
                                    @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Office State <span class="label_start">*</span></label>
                                    <select required name="office_state" class="form-control" id="office_state">
                                        <option disabled selected value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->name }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Office Zipcode <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('zipcode') }}" required name="office_zipcode" class="form-control" id="office_zipcode">
                                    @error('zipcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="student_data">
                            <strong>Student Address <i class="fa-solid fa-arrow-down"></i></strong>
                            {{-- Use Office Address: copies office address into student address fields --}}
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-12">
                                    <div class="form-group" style="margin-bottom:10px;">
                                        <label style="cursor:pointer; font-weight:600;">
                                            <input type="checkbox" id="use_office_address">
                                            Use Office Address
                                        </label>
                                        <small style="display:block; opacity:1; color:#fff">(Copy office address to student address fields)</small>
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Student Address (please avoid special characters)<span class="label_start">*</span></label>
                                    <textarea required class="form-control" name="address" id="student_address">{{ old('address') }}</textarea>
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">City <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('city') }}" required name="city" class="form-control" id="student_city">
                                    @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">State <span class="label_start">*</span></label>
                                    <select required name="state" class="form-control" id="student_state">
                                        <option disabled selected value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->name }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Zipcode <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('zipcode') }}" required name="zipcode" class="form-control" id="student_zipcode">
                                    @error('zipcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label" for="email">Student Email <span class="label_start">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" required class="form-control" id="email" placeholder="example@website.com">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Student Telephone <span class="label_start">*</span></label>
                                    <input type="text" value="{{ old('phone') }}" required name="phone" class="form-control">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" id="profession_row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label" for="uname">Profession <span class="label_start">*</span></label>
                                    <select required name="category_id" class="form-control" id="category_id">
                                        <option disabled selected>Select Profession</option>
                                        <option value="3">Chiropractor</option>
                                        <optgroup label="Chiropractic Assistant">
                                            <option value="2">Initial Training</option>
                                            <option value="1">CE</option>
                                        </optgroup>
                                        <option value="4">Massage Therapist</option>
                                        <option value="6">Government Employee</option>
                                        <option value="5">General Public</option>
                                    </select>
                                    @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row" id="is_license_row" style="display:none">
                            <div class="col-md-12">
                                <input type="checkbox" name="is_license" id="is_license">

                                <label for="">Not Applicable</label>
                            </div>
                        </div>
                       <div class="row" id="license_no_row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                    <label class="control-label" for="uname">Professional License No. </label>
                                    <input type="text" value="{{ old('licence_no') }}"  name="licence_no" class="form-control">
                                    @error('licence_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                               <div class="form-group">
                                    <label class="control-label" for="uname">License Renewal Date. </label>
                                    <input type="text" value="{{ old('renewal_data') }}"  name="renewal_data" class="form-control">
                                    @error('renewal_data')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group positionRelative">
                                    <label class="control-label" for="pwd">Password <span class="label_start">*</span></label>
                                    <input type="password" name="password" required class="form-control" id="password" placeholder="xxxxxxxxx">
                                    <div class="input-group-append toggle-password">
                                        <span class="input-group-text mdi mdi-eye-outline"></span>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <meter max="4" id="password-strength-meter"></meter>
                                    <p style='display:none;' id="password-strength-text"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group positionRelative">
                                    <label class="control-label" for="pwd">Confirm Password <span class="label_start">*</span></label>
                                    <input type="password" required name="password_confirmation" class="form-control" id="pwd" placeholder="xxxxxxxxx">
                                    <div class="input-group-append toggle-password">
                                        <span class="input-group-text mdi mdi-eye-outline"></span>
                                    </div>
                                    @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                    @error('g-recaptcha-response')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                {{-- {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!} --}}
                                <!--<div class="g-recaptcha" id="feedback-recaptcha" -->
                                <!--    data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}">-->
                                <!--</div>-->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">

                                <div class="form-group">
                                    <button type="submit" class="btn btn-default g-recaptcha" 
                                    data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}" 
                                    data-callback='onSubmit' 
                                    data-action='submit'
        >Sign Up</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>

    <script>
    function onSubmit(token) {
    document.getElementById("registerForm").submit();
}
        var strength = {
            0: "Worst ☹",
            1: "Bad ☹",
            2: "Weak ☹",
            3: "Good ☺",
            4: "Strong ☻"
        }

        var password = document.getElementById('password');
        var meter = document.getElementById('password-strength-meter');
        var text = document.getElementById('password-strength-text');

        password.addEventListener('input', function()
        {
            var val = password.value;
            var result = zxcvbn(val);

            // Update the password strength meter
            meter.value = result.score;

            // Update the text indicator
            if(val !== "") {
                text.innerHTML = "Strength: " + "<strong>" + strength[result.score] + "</strong>" + "<span class='feedback'>" + result.feedback.warning + " " + result.feedback.suggestions + "</span";
            }
            else {
                text.innerHTML = "";
            }
        });

        $('#category_id').change(function(){//if category_id is 1 then license no should be mandatory and for other category_id show checkbox
            if($(this).val() == 1){
                $("input[name='licence_no']").prop('disabled', false);
                $("input[name='licence_no']").prop('required', true);
                $('#is_license_row').css('display', 'none');
            }else{
                $('#is_license_row').css('display', 'block');
                $("input[name='licence_no']").prop('required', false);
                $("input[name='licence_no']").prop('disabled', true);
                $('#is_license').prop('checked', true);

            }
        });
        $('#is_license').click(function(){
            if($(this).is(':checked')){
                $("input[name='license_no']").val("");
                $("input[name='licence_no']").prop('disabled', true);
            }else{
                $("input[name='licence_no']").prop('disabled', false);
            }
            $('#registerForm').validate().element("input[name='licence_no']")
        });

        // Use Office Address: copy OFFICE fields -> STUDENT fields (correct fix for the bug)
        $('#use_office_address').change(function(){
            if($(this).is(':checked')){
                $('#student_address').val($('#office_address').val());
                $('#student_city').val($('#office_city').val());
                $('#student_state').val($('#office_state').val());
                $('#student_zipcode').val($('#office_zipcode').val());
                $('#student_suite').val($('#office_suite').val());
            }
        });

        // Use my home address: copy STUDENT fields -> OFFICE fields
        $('#use_home_address').change(function(){
            if($(this).is(':checked')){
                $('#office_address').val($('#student_address').val());
                $('#office_city').val($('#student_city').val());
                $('#office_state').val($('#student_state').val());
                $('#office_zipcode').val($('#student_zipcode').val());
                $('#office_suite').val($('#student_suite').val());
            }
        });
    </script>
    @push('scripts')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.7.95/css/materialdesignicons.css" rel="stylesheet"/>
        <style>
            .positionRelative {
                position: relative;
            }
            .toggle-password {
                position: absolute;
                top: 41px;
                right: 13px;
                font-size: 19px;
                color: #919191;
            }
        </style>
        <script>
            $('.toggle-password').click(function(){
                $(this).children().toggleClass('mdi-eye-outline mdi-eye-off-outline');
                let input = $(this).prev();
                input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
            });
        </script>
    @endpush
@endpush
