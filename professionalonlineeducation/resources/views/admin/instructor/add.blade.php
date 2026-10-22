@extends('admin.layouts.app')

@section('title')
    Instructors
@endsection
@section('content')
    <div class="content-main " id="content-main">
        @if($errors->any())
        <ul class="text-danger">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                        <div class="box">
                            <div class="box-header">
                                @if(file_exists(@$edit_instructor->resume))
                                    <a href="{{ asset($edit_instructor->resume) }}" class="btn btn-primary float-right" download>Curriculum Vitae</a>
                                @endif
                                <h2>Instructors List</h2>
                            </div>
                            <div class="box-body">
                                <form action="{{ route('admin.instructors.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="">First Name</label><br />
                                            @error('')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="text" class="form-control" placeholder="Enter first name" value="{{ old('first_name') ?? @$edit_instructor->first_name }}" name="first_name" id="first_name" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="">Last Name</label><br />
                                            @error('last_name')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="text" class="form-control" placeholder="Enter last name" value="{{ old('last_name') ?? @$edit_instructor->last_name }}" name="last_name" id="last_name" required>
                                        </div>
                                    </div>

                                    <div class="row" id="title_div">
                                        @if(isset($is_update))
                                            @foreach($edit_instructor->titles AS $title)
                                                <div>
                                                    <div class="col-md-11 form-group">
                                                        <label for="">Title</label><br />
                                                        @error('title')
                                                            <span class="text-danger"><small>{{ $message }}</small></span>
                                                        @enderror
                                                        <input type="text" class="form-control" placeholder="Enter title" value="{{ $title->title }}" name="title[]" id="title" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        @if($loop->first)
                                                            <button class="btn btn-primary" style="margin-top:20px" id="add_title">+</button>
                                                        @else 
                                                            <button class="btn btn-danger remove_title" style="margin-top:20px" type="button">-</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                        <div>
                                            <div class="col-md-11 form-group">
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
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="">Phone No</label><br />
                                            @error('phone_no')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="text" class="form-control" placeholder="Enter phone no" value="{{ old('phone_no') ?? @$edit_instructor->phone_no }}" name="phone_no" id="phone_no" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="">Personal Email</label><br />
                                            @error('email')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="Email" class="form-control" placeholder="Enter email" value="{{ old('email') ?? @$edit_instructor->email }}" name="email" id="email" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="">Office Phone No</label><br />
                                            @error('phone_no')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="text" class="form-control" placeholder="Enter office phone no" value="{{ old('office_phone_no') ?? @$edit_instructor->office_phone_no }}" name="office_phone_no" id="office_phone_no" >
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="">Office Email</label><br />
                                            @error('office_email')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="Email" class="form-control" placeholder="Enter office email" value="{{ old('office_email') ?? @$edit_instructor->office_email }}" name="office_email" id="office_email" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="">Password</label><br />
                                            @error('password')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="password" class="form-control" placeholder="********" name="password" id="password" @unless(@$is_update) required @endunless>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="">Confirm Password</label><br />
                                            @error('password_confirmation')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="password" class="form-control" placeholder="********" name="password_confirmation" id="password_confirmation" @unless(@$is_update) required @endunless>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-6">
                                            <label for="">City</label>
                                            <input type="text" class="form-control" placeholder="Enter city" name="city" id="city">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">State</label>
                                            <input type="text" class="form-control" placeholder="Enter state" name="state" id="state">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Zip Code</label>
                                            <input type="text" class="form-control" placeholder="Enter zip code" name="zip_code" id="zip_code">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Address</label>
                                            <input type="text" class="form-control" placeholder="Enter address" name="address" id="address">
                                        </div>
                                    </div> --}}
                                    {{-- <div class="row">
                                        <div class="col-md-12">
                                            <label for="">City, State, Zip Code , Street Address</label><br />
                                            @error('address')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="text" class="form-control" Placeholder="Enter city, state, zip code, street address" value="{{ old('address') ?? @$edit_instructor->address }}" name="address" id="address" required>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">City</label>
                                            @error('city')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="text" class="form-control" placeholder="Enter city" value="{{ old('city') ?? @$edit_instructor->city }}" name="city" id="city">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">State</label>
                                            @error('state')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            {{-- <input type="text" class="form-control" placeholder="Enter state" value="{{ old('state') }}" name="state" id="state"> --}}
                                            <select class="form-control select2Dynamic" name="state" id="state">
                                                @foreach($states AS $state)
                                                    <option value="{{ $state->name }}"  @if(@$edit_instructor->state == $state->name) selected @endif>{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Zip Code</label>
                                            @error('zip_code')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="text" class="form-control" placeholder="Enter zip code" value="{{ old('zip_code') ?? @$edit_instructor->zip_code }}" name="zip_code" id="zip_code">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Address</label>
                                            @error('address')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="text" class="form-control" placeholder="Enter address" value="{{ old('address') ?? @$edit_instructor->address }}" name="address" id="address">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="">License Profession</label><br />
                                            @error('profession')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <select class="form-control select2Dynamic" name="profession" id="profession">
                                                @foreach($professions AS $profession)
                                                    <option value="{{ $profession->name }}" @if(@$edit_instructor->profession) selected @endif>{{ $profession->name }}</option>
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
                                            <textarea class="form-control" name="background" id="background" cols="10" rows="10" required>{{ old('background') ?? @$edit_instructor->background }}</textarea>
                                        </div> --}}
                                        <div class="col-md-12">
                                            <label for="">Bio</label><br />
                                            @error('bio')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <textarea class="form-control" name="bio" id="bio" cols="10" rows="10" required>{{ old('bio')  ?? @$edit_instructor->bio }}</textarea>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Office Start Time</label><br />
                                            @error('office_start_time')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="time" class="form-control"  value="{{ old('office_start_time') ?? @$edit_instructor->office_start_time }}" name="office_start_time" id="office_start_time" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Office End Time</label><br />
                                            @error('office_end_time')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="time" class="form-control" value="{{ old('office_end_time') ?? @$edit_instructor->office_end_time }}"  name="office_end_time" id="office_end_time" required>
                                        </div>
                                    </div> --}}
                                    @foreach(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"] as $key=>$day)
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <input type="checkbox" name="days[]" id="day_{{ $loop->index }}" value="{{ $day }}" class="day-checkbox" @if(isset($edit_instructor) && in_array($day, @$edit_instructor->officeTimings->pluck('day')->toArray())) checked @endif>
                                            <label for="day_{{ $loop->index }}">{{ $day }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="{{ $day }}_office_start_time">Office Start Time</label><br />
                                            @error($day.'_office_start_time')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="time" class="form-control office-time"  name="office_start_time[]" id="{{ $day }}_office_start_time" value="{{ @$edit_instructor->officeTimings ? @$edit_instructor->officeTimings->where('day', $day)->first()->start_time : '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="{{ $day }}_office_end_time">Office End Time</label><br />
                                            @error($day.'_office_end_time')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="time" class="form-control office-time"  name="office_end_time[]" id="{{ $day }}_office_end_time" value="{{  @$edit_instructor->officeTimings ? @$edit_instructor->officeTimings->where('day', $day)->first()->end_time : '' }}">
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
                                            <option value="yes" @if(@$edit_instructor->is_license == 'yes') selected @endif>Yes</option>
                                            <option value="no" @if(@$edit_instructor->is_license == 'no') selected @endif>No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">State Verified Date</label><br />
                                        @error('state_verified_date')
                                            <span class="text-danger"><small>{{ $message }}</small></span>
                                        @enderror
                                        <input type="date" class="form-control" value="{{ old('state_verified_date') ?? @$edit_instructor->state_verified_date }}"  name="state_verified_date" id="state_verified_date" required>
                                    </div>
                                </div>
                                @if(isset($is_update) && $edit_instructor->is_license == 'yes' && !empty($edit_instructor->licenses))
                                    @foreach($edit_instructor->licenses AS $license)
                                    
                                        <div class="row" id="license_parent_div">
                                            <div class="col-md-12">
                                                @if($loop->first)
                                                    <button class="btn btn-primary" style="float:right" id="add_license_row">+</button>
                                                @else
                                                    <button class="btn btn-danger remove_license_row" style="float:right">x</button>
                                                @endif
                
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="">License No</label><br />
                                                        <input type="text" class="form-control" placeholder="Enter license code" value="{{ $license->license_no }}" name="license_no[]" id="license_no" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">License Verification Date</label><br />
                                                        <input type="date" class="form-control" value="{{ $license->verification_date }}"  name="license_verification_date[]" id="">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">License State</label><br />
                                                        <select class="form-control select2Dynamic" name="state_id[]" id="state">
                                                            @foreach($states AS $state)
                                                                <option value="{{ $state->id }}" {{ old('state') == $state->name ? 'selected' : '' }} @if($license->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                                                            @endforeach
                                                        </select>                                                    
                                                    </div>
                                                    </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else 
                                <div class="row" id="license_parent_div" @if(@$edit_instructor->is_license == 'no') style="display:none" @endif>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" style="float:right" id="add_license_row">+</button>
        
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">License No</label><br />
                                                <input type="text" class="form-control" placeholder="Enter license code" value="" name="license_no[]" id="license_no" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">License Verification Date</label><br />
                                                <input type="date" class="form-control" value=""  name="license_verification_date[]" id="">
                                            </div>
                                        </div>
                                    </div>
                                </div>                                        
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Contact Received Signed Date</label><br />
                                        @error('contract_received_signed_date')
                                            <span class="text-danger"><small>{{ $message }}</small></span>
                                        @enderror
                                        <input type="date" class="form-control"  value="{{ old('contract_received_signed_date') ?? @$edit_instructor->contract_received_signed_date }}" name="contract_received_signed_date" id="contract_received_signed_date" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Contact Term Date</label><br />
                                        @error('contract_term_date')
                                            <span class="text-danger"><small>{{ $message }}</small></span>
                                        @enderror
                                        <input type="date" class="form-control"  value="{{ old('contract_term_date') ?? @$edit_instructor->contract_term_date }}" name="contract_term_date" id="contract_term_date" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Signed W9 form received</label><br />
                                        @error('w_received_date')
                                            <span class="text-danger"><small>{{ $message }}</small></span>
                                        @enderror
                                        <input type="date" class="form-control" value="{{ old('w_received_date') ?? @$edit_instructor->w_received_date }}"  name="w_received_date" id="w_received_date" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Email Setup Date</label><br />
                                        @error('email_setup_date')
                                            <span class="text-danger"><small>{{ $message }}</small></span>
                                        @enderror
                                        <input type="date" class="form-control" value="{{ old('email_setup_date') ?? @$edit_instructor->email_setup_date }}"  name="email_setup_date" id="email_setup_date" required>
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
                                                <option value="{{ $catalog->id }}" @if(isset($is_update) && in_array($catalog->id, @$edit_instructor->catalogs->pluck('id')->toArray())) selected @endif>{{ $catalog->title }}</option>
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
                                </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-file">
                                                <label class="custom-file-label" for="customFile">Choose Photo</label><br />
                                                @error('photo')
                                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                                @enderror
                                                <input type="file" class="form-control custom-file-input" id="photo" name="photo" onchange="preview('photo_img')" @unless(@$edit_instructor) @endunless>
                                            </div>
                                            <img @unless(@$is_update) style="display:none" @endunless id="photo_img" src="{{ asset(@$edit_instructor->photo) }}" width="100px" height="100px"/>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="custom-file">
                                                <label class="custom-file-label" for="customFile">W9 Form</label><br />
                                                @error('w9_form')
                                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                                @enderror
                                                <input type="file" class="form-control custom-file-input" id="w9_form" name="w9_form" onchange="preview('w9_form_img')" @unless(@$edit_instructor) @endunless>
                                            </div>
                                            @if(@getimagesize(@$edit_instructor->w9_form))
                                                <img @unless(@$is_update) style="display:none" @endunless id="w9_form_img" src="{{ asset(@$edit_instructor->w9_form) }}" width="100px" height="100px"/>
                                            @else 
                                                <a href="{{ asset(@$edit_instructor->w9_form) }}" class="btn btn-primary mt-2" download>Download</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-file">
                                                <label class="custom-file-label" for="customFile">Instructor Contract</label><br />
                                                @error('resume')
                                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                                @enderror
                                                <input type="file" class="form-control custom-file-input" id="resume" name="resume" onchange="preview('instructor_contract_img')" @unless(@$edit_instructor) @endunless>
                                            </div>
                                            @if(@getimagesize(@$edit_instructor->resume))
                                                <img @unless(@$is_update) style="display:none" @endunless id="instructor_contract_img" src="{{ asset(@$edit_instructor->resume) }}" width="100px" height="100px"/>
                                            @else 
                                                <a href="{{ asset(@$edit_instructor->resume) }}" class="btn btn-primary mt-2" download>Download</a>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="custom-file">
                                                <label class="custom-file-label" for="customFile">Instructor Information And Bio Form</label><br />
                                                @error('instructor_information')
                                                    <span class="text-danger"><small>{{ $message }}</small></span>
                                                @enderror
                                                <input type="file" class="form-control custom-file-input" id="instructor_information" name="instructor_information" onchange="preview('instructor_information_img')" @unless(@$edit_instructor) @endunless>
                                            </div>
                                            @if(@getimagesize(@$edit_instructor->instructor_information))
                                                <img @unless(@$is_update) style="display:none" @endunless id="instructor_information_img" src="{{ asset(@$edit_instructor->instructor_information) }}" width="100px" height="100px"/>
                                            @else 
                                                <a href="{{ asset(@$edit_instructor->instructor_information) }}" class="btn btn-primary mt-2" download>Download</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row p-5" style="background-color:#D5D8DC">
                                        <div class="col-md-6">
                                            <label for="">Percentage On Revenue</label><br />
                                            @error('revenue_percentage')
                                                <span class="text-danger"><small>{{ $message }}</small></span>
                                            @enderror
                                            <input type="number" class="form-control" value="{{ old('revenue_percentage') ?? @$edit_instructor->revenue_percentage }}" name="revenue_percentage" id="revenue_percentage" required style="background -color:pink">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Can add course</label><br />
                                            <select class="form-control" name="is_add_course" id="is_add_course" required style="background -color:pink">
                                                <option value="1" @if(@$edit_instructor->is_add_course == 1) selected @endif>Yes</option>
                                                <option value="0" @if(@$edit_instructor->is_add_course == 0) selected @endif>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach($checkbox_arr AS $index=>$checkbox)
                                            <div class="col-md-2">
                                                <input type="checkbox" name="checkbox_arr[{{ $index }}]" @if(isset(json_decode($edit_instructor->checkbox_arr, true)[$index])) checked @endif/>
                                                <label for="">{{ $checkbox }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="hidden" name="is_admin" value="1">
                                            <input type="hidden" name="instructor_id" value={{ base64_encode(@$edit_instructor->id) }}>
                                            <input type="submit" class="btn btn-primary" style="margin-top:10px; float:right">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                </div>

            </div>

        </div>
        <!-- ############ Main END-->
    </div>


@endsection
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            let is_update = {{ @$is_update }}
            let is_license = "{{ @$edit_instructor->is_license }}";
            
            if(is_license == 'no'){
                $('input[name="license_no[]"]').removeAttr('required');
            }

            $('.select2').select2();
        });

        $('#add_title').click(function(event){
            event.preventDefault();
            var html = `<div>
                                    <div class="col-md-11 form-group">
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

        //         // Function to handle checkbox change event
        //         $(".day-checkbox").on("change", function() {
        //     var index = $(this).attr("id").split("_")[1];
        //     var isChecked = $(this).prop("checked");
        //     var $officeTimeInputs = $(".office-time");
            
        //     if (isChecked) {
        //         // Add 'required' attribute to office time inputs
        //         $officeTimeInputs.filter('[id$="_office_start_time"],[id$="_office_end_time"]').eq(index).prop("required", true);
        //     } else {
        //         // Remove 'required' attribute from office time inputs
        //         $officeTimeInputs.filter('[id$="_office_start_time"],[id$="_office_end_time"]').eq(index).prop("required", false);
        //     }
        // });
        $(document).ready(function() {
            $('.select2Dynamic').select2({
                tags:true,
            });
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
                            <div class="col-md-3">
                                <label for="">License Verification Date</label><br />
                                <input type="date" class="form-control" value="{{ @$license->verification_date }}"  name="license_verification_date[]" id="">
                            </div>
                            <div class="col-md-3">
                                <label for="">License State</label><br />
                                <select class="form-control select2Dynamic" name="state_id[]" id="state">
                                    @foreach($states AS $state)
                                        <option value="{{ @$state->id }}" {{ old('state') == $state->name ? 'selected' : '' }} @if(@$license->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                                    @endforeach
                                </select>                                                    </div>
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
