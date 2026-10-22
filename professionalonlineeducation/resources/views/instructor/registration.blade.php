@extends('layouts.web')
@section('title','Transform Career | Professional Online Education Enroll')
@section('keywords','Professional Online Education Enroll')
@section('description','Ready to take the next step in your chiropractic career? Enroll in our professional online education enroll program and gain the skills you need to succeed.')
@section('content')
                                    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}

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
            {{-- @if($errors->any())
                @foreach($errors->all() AS $error)
                    <p class="text-danger">{{ $error }}</p>
                @endforeach
            @endif --}}
            @if($errors->any())
                <ul class="text-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            @if(session()->has('success'))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-success my_note_txt" role="alert">
                        {{ session()->get('success') }}
                        </div>
                    </div>
                </div>
            @endif
            <div class="row style_row">
                {{-- <div class="col-sm-7">
                    <div class="block_title" data-aos="fade-right">
                        <h2>Sign Up</h2>
                        <p>Welcome to Canham college online chiropractic assistant training company login page! Our comprehensive online programs provide expert education and training to those interested in the chiropractic industry. Our online platform offers a flexible and accessible learning experience, allowing students to study anytime and anywhere, at their own pace. Our expert instructors use interactive methods of teaching to provide a hands-on and engaging learning experience. We offer personalized attention and support to each student to ensure they receive the best possible education. Enroll with us today and take the first step towards becoming a skilled and knowledgeable chiropractic assistant!</p>

                        <a href="{{ route('login') }}" class="btn btn-default">Sign In</a>
                    </div>
                </div> --}}
                <div class="col-sm-12">
                    <form action="{{ route('instructor.store_form') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="">First Name</label><br />
                                @error('')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="text" class="form-control" placeholder="Enter first name" value="{{ old('first_name') }}" name="first_name" id="first_name" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="">Last Name</label><br />
                                @error('last_name')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="text" class="form-control" placeholder="Enter last name" value="{{ old('last_name') }}" name="last_name" id="last_name" required>
                            </div>
                            <div class="col-md-4">
                                <div class="row" id="title_div">
                                    <div>
                                        <div class="col-md-10 form-group">
                                            <label for="">Title</label><br />
                                            @error('title')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                             @enderror
                                            <input type="text" class="form-control" placeholder="Enter title" value="" name="title[]" id="title" required>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-primary" style="margin-top:20px" id="add_title">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Phone No</label><br />
                                @error('phone_no')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="text" class="form-control" placeholder="Enter phone no" value="{{ old('phone_no') }}" name="phone_no" id="phone_no" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Personal Email</label><br />
                                @error('email')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="Email" class="form-control" placeholder="Enter email" value="{{ old('email') }}" name="email" id="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Office Phone No</label><br />
                                @error('office_phone_no')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="text" class="form-control" placeholder="Enter phone no" value="{{ old('office_phone_no') }}" name="office_phone_no" id="office_phone_no" >
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Office Email</label><br />
                                @error('office_email')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="Email" class="form-control" placeholder="Enter email" value="{{ old('office_email') }}" name="office_email" id="office_email" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Password</label><br />
                                @error('password')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="password" class="form-control" placeholder="********" name="password" id="password" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Confirm Password</label><br />
                                @error('password_confirmation')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="password" class="form-control" placeholder="********" name="password_confirmation" id="password_confirmation" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Street Address</label>
                                @error('address')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="text" class="form-control" placeholder="Enter street address" value="{{ old('address') }}" name="address" id="address">
                            </div>
                            <div class="col-md-6">
                                <label for="">City</label>
                                @error('city')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="text" class="form-control" placeholder="Enter city" value="{{ old('city') }}" name="city" id="city">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">State</label>
                                @error('state')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                {{-- <input type="text" class="form-control" placeholder="Enter state" value="{{ old('state') }}" name="state" id="state"> --}}
                                <select class="form-control select2Dynamic" name="state" id="state">
                                    @foreach($states AS $state)
                                        <option value="{{ $state->name }}" {{ old('state') == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6  form-group">
                                <label for="">Zip Code</label>
                                @error('zip_code')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="text" class="form-control" placeholder="Enter zip code" value="{{ old('zip_code') }}" name="zip_code" id="zip_code">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12  form-group">
                                <label for="">License Profession</label><br />
                                @error('profession')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <select class="form-control select2Dynamic" name="profession" id="profession">
                                    @foreach($professions AS $profession)
                                        <option value="{{ $profession->name }}" {{ old('profession') == $profession->name ? 'selected' : '' }}>{{ $profession->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="col-md-6">
                                <label for="">Background</label><br />
                                @error('background')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <textarea class="form-control" name="background" id="background" style="height:100px" required>{{ old('background') }}</textarea>
                            </div> --}}
   
                            <div class="col-md-12">
                                <label for="">Bio</label><br />
                                @error('bio')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <textarea class="form-control" name="bio" id="bio" style="height:100px" required>{{ old('bio') }}</textarea>
                            </div>
                        </div>
                        {{-- @foreach(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"] as $day)
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input type="checkbox" name="days[]" id="day_{{ $loop->index }}" value="{{ $day }}">
                                    <label for="day_{{ $loop->index }}">{{ $day }}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="{{ $day }}_office_start_time">Office Start Time</label><br />
                                    @error($day.'_office_start_time')
                                        <span class="text-danger"><small>{{ $message }}</small></span>
                                    @enderror
                                    <input type="time" class="form-control" value="{{ old($day.'_office_start_time') }}" name="office_start_time[]" id="{{ $day }}_office_start_time">
                                </div>
                                <div class="col-md-6">
                                    <label for="{{ $day }}_office_end_time">Office End Time</label><br />
                                    @error($day.'_office_end_time')
                                        <span class="text-danger"><small>{{ $message }}</small></span>
                                    @enderror
                                    <input type="time" class="form-control" value="{{ old($day.'_office_end_time') }}" name="office_end_time[]" id="{{ $day }}_office_end_time">
                                </div>
                            </div>
                        @endforeach --}}
                        @foreach(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"] as $day)
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input type="checkbox" name="days[]" id="day_{{ $loop->index }}" value="{{ $day }}" class="day-checkbox">
                                    <label for="day_{{ $loop->index }}">{{ $day }}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="{{ $day }}_office_start_time">Office Start Time</label><br />
                                    @error($day.'_office_start_time')
                                        <span class="text-danger"><small>{{ $message }}</small></span>
                                    @enderror
                                    <input type="time" class="form-control office-time" value="{{ old($day.'_office_start_time') }}" name="office_start_time[]" id="{{ $day }}_office_start_time">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="{{ $day }}_office_end_time">Office End Time</label><br />
                                    @error($day.'_office_end_time')
                                        <span class="text-danger"><small>{{ $message }}</small></span>
                                    @enderror
                                    <input type="time" class="form-control office-time" value="{{ old($day.'_office_end_time') }}" name="office_end_time[]" id="{{ $day }}_office_end_time">
                                </div>
                            </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Do you have license ?</label><br />
                                @error('is_license')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <select class="form-control" name="is_license" id="is_license" required>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="">State Verified Date</label><br />
                                @error('state_verified_date')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="date" class="form-control" value="{{ old('state_verified_date') }}"  name="state_verified_date" id="state_verified_date">
                            </div> --}}
                        </div>
                        <div class="row" id="license_parent_div">
                            <div class="col-md-12">
                                <button class="btn btn-primary" style="float:right" id="add_license_row">+</button>

                            </div>
                            <div class="col-md-12  form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">License No</label><br />
                                        <input type="text" class="form-control" placeholder="Enter license code" value="" name="license_no[]" id="license_no" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">License State</label><br />
                                        {{-- @error('state_verified_date')
                                            <span class="text-danger"><small>{{ $message }}</small></span>
                                        @enderror --}}
                                        {{-- <input type="date" class="form-control" value=""  name="license_verification_date[]" id=""> --}}
                                        <select class="form-control select2Dynamic" name="state_id[]" id="state">
                                            @foreach($states AS $state)
                                                <option value="{{ $state->id }}" {{ old('state') == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Timezones</label><br />
                                @error('timezone')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <select class="form-control select2" name="timezone" id="timezone" required>
                                    @foreach($timezones AS $timezone)
                                        <option value="{{ $timezone->id }}">{{ $timezone->name }} ({{ $timezone->abbreviation }}{{ $timezone->offset }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <label for="">Contact Received Signed Date</label><br />
                                @error('contract_received_signed_date')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="date" class="form-control"  value="{{ old('contract_received_signed_date') }}" name="contract_received_signed_date" id="contract_received_signed_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">Contact Term Date</label><br />
                                @error('contract_term_date')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="date" class="form-control"  value="{{ old('contract_term_date') }}" name="contract_term_date" id="contract_term_date" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Signed W9 form received</label><br />
                                @error('w_received_date')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="date" class="form-control" value="{{ old('w_received_date') }}"  name="w_received_date" id="w_received_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">Email Setup Date</label><br />
                                @error('email_setup_date')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="date" class="form-control" value="{{ old('email_setup_date') }}"  name="email_setup_date" id="email_setup_date" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Catalogs</label><br />
                                @error('catalogs')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <select class="form-control select2" name="catalogs[]" id="catalogs" required multiple>
                                    @foreach($catalogs AS $catalog)
                                        <option value="{{ $catalog->id }}">{{ $catalog->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Timezones</label><br />
                                @error('timezone')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <select class="form-control select2" name="timezone" id="timezone" required>
                                    @foreach($timezones AS $timezone)
                                        <option value="{{ $timezone->id }}">{{ $timezone->name }} ({{ $timezone->abbreviation }}{{ $timezone->offset }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-file">
                                    <label class="custom-file-label" for="customFile">Choose Photo</label><br />
                                    @error('photo')
                                        <span class="text-danger"><small>{{ $message }}</small></span>
                                    @enderror
                                    <input type="file" class="form-control custom-file-input" id="photo" name="photo" onchange="preview('photo_img')" required>
                                </div>
                                <img style="display:none" id="photo_img" src="" width="100px" height="100px"/>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-file">
                                    <label class="custom-file-label" for="customFile">W9 Form</label><br />
                                    @error('w9_form')
                                        <span class="text-danger"><small>{{ $message }}</small></span>
                                    @enderror
                                    <input type="file" class="form-control custom-file-input" id="w9_form" name="w9_form" onchange="preview('w9_form_img')" required>
                                </div>
                                <img style="display:none" id="w9_form_img" src="" width="100px" height="100px"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-file">
                                    <label class="custom-file-label" for="customFile">Curriculum Vitae</label><br />
                                    @error('resume')
                                        <span class="text-danger"><small>{{ $message }}</small></span>
                                    @enderror
                                    <input type="file" class="form-control custom-file-input" id="resume" name="resume" onchange="preview('instructor_contract_img')" required>
                                </div>
                                <img style="display:none" id="instructor_contract_img" src="" width="100px" height="100px"/>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-file">
                                    <label class="custom-file-label" for="customFile">Instructor Information And Bio Form</label><br />
                                    @error('instructor_information')
                                        <span class="text-danger"><small>{{ $message }}</small></span>
                                    @enderror
                                    <input type="file" class="form-control custom-file-input" id="instructor_information" name="instructor_information" onchange="preview('instructor_information_img')" required>
                                </div>
                                <img style="display:none" id="instructor_information_img" src="" width="100px" height="100px"/>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <label for="">Percentage On Revenue</label><br />
                                @error('revenue_percentage')
                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                @enderror
                                <input type="number" class="form-control" value="{{ old('revenue_percentage') }}" name="revenue_percentage" id="revenue_percentage" required>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary" style="margin-top:10px; float:right">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        $(document).ready(function() {
            $('.select2Dynamic').select2({
                tags:true,
            });
        });

        $('#add_title').click(function(event){
            event.preventDefault();
            var html = `<div>
                                    <div class="col-md-10 form-group">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" placeholder="Enter title" name="title[]" required>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger remove_title" style="margin-top:20px" type="button">-</button>
                                    </div>
                                </div>`;

            $('#title_div').append(html);
        }); 

        $(document).on('click', '.remove_title', function(){
            $(this).parent().parent().remove();
        });

        function preview(image_id) {
            $('#'+image_id).css('display', 'block');
            $('#'+image_id).attr('src', URL.createObjectURL(event.target.files[0]));
        }

        // Function to handle checkbox change event
        $(".day-checkbox").on("change", function() {
            var index = $(this).attr("id").split("_")[1];
            var isChecked = $(this).prop("checked");
            var $officeTimeInputs = $(".office-time");
            
            if (isChecked) {
                // Add 'required' attribute to office time inputs
                $officeTimeInputs.filter('[id$="_office_start_time"],[id$="_office_end_time"]').eq(index).prop("required", true);
            } else {
                // Remove 'required' attribute from office time inputs
                $officeTimeInputs.filter('[id$="_office_start_time"],[id$="_office_end_time"]').eq(index).prop("required", false);
            }
        });

        $('#add_license_row').click((e)=>{
            e.preventDefault();
            let html = `<div class="col-md-12"> <div class="col-md-12">
                                <button class="btn btn-danger remove_license_row" style="float:right">x</button>
                            </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">License No</label><br />
                                <input type="text" class="form-control" placeholder="Enter license code" value="" name="license_no[]" id="license_no" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">License State</label><br />
                                <select class="form-control select2Dynamic" name="state_id[]" id="state">
                                @foreach($states AS $state)
                                    <option value="{{ $state->id }}" {{ old('state') == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </div>
                        </div><div>
                    `;
        $('#license_parent_div').append(html);

        });

        $(document).on('click', '.remove_license_row', function(e){
            e.preventDefault();
            $(this).parent().parent().remove();
        });

        $('#is_license').change(function(){
            if($(this).val() == 'no'){
                $('#license_parent_div').css('display', 'none');
                $('input[name="license_no[]"]').removeAttr('required');

            }else{
                $('#license_parent_div').css('display', 'block');
                $('input[name="license_no[]"]').attr('required', true);
            }
        });
    </script>
@endpush
