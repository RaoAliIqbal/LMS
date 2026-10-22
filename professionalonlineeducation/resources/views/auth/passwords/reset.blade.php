@extends('layouts.web')

@section('content')




<section class="login_section" style="padding: 50px 0;">
  <div class="container">
    <div class="row style_row">
      
            <div class="col-sm-6 whole_right">
                <h3>Forgot Your Password</h3>
                <p>Don’t worry. Resetting your password is easy. Just tell us the email address you registered with
                    us.</p>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group row">
                        <label for="email" class="col-md-12 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-12 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-12 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-12">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary cstm_btn">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </form>
                <!-- <h6 class="backToHome"><i class="fa fa-home" aria-hidden="true"></i> Back To <a href="/">HomePage</a>
                </h6> 
                <h6  class="backToHome">Already a Member? <a href="{{ route('login') }}" class="white_anchor">LOGIN</a></h6>-->
            </div>
            <div class="col-sm-6 color_left">
                <div class="right_sec color_right">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    h6.backToHome {
        font-size: 15px;
    }
</style>

@endsection
