@extends('layouts.web')

@section('content')

    <section class="top_section_banner" style="background-image:url('{{ url("webAssets/images/banner-homepage.jpg") }}');">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="top_banner_titles">
                        <h3>Sign In</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section  class="login_section" style="padding: 50px 0;">
        <div class="container">
            <div class="row style_row">
                <div class="col-sm-5">
                    <form method="POST" action="{{ route('instructor.login') }}">

                        @include('layouts.partials.alertBox')
                        @csrf
                        <div class="form-group">
                            <label class="control-label" for="email">Email <span class="label_start">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required  class="form-control" placeholder="example@website.com">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group positionRelative" >
                            <label class="control-label" for="pwd">Password <span class="label_start">*</span></label>
                            <input type="password" name="password" required id="password" class="form-control" placeholder="xxxxxxxxx">
                            <div class="input-group-append toggle-password">
                                <span class="input-group-text mdi mdi-eye-outline"></span>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox"> Remember me</label>
                            {{-- @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="make_left"/>forget Password</a>
                            @endif --}}
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
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
