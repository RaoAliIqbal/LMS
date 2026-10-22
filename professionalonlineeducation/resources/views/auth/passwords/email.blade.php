@extends('layouts.web')

@section('content')

    <section  class="login_section" style="padding: 50px 0;">
        <div class="container">
            <div class="row style_row">
                <div class="col-sm-5">
                    <h3>Forgot Your Password</h3>
                    <p>Don’t worry. Resetting your password is easy. Just tell us the email address you registered with
                        us.</p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        @csrf
                        <div class="form-group">
                            <label class="control-label" for="email">Email <span class="label_start">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required  class="form-control" placeholder="example@website.com">
                            @error('email')
                            <span class="invalid-feedback" role="alert" style="display:block;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
