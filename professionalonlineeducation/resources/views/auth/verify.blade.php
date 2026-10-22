@extends('layouts.auth')

@section('content')
    @php $url = redirect()->intended()->getTargetUrl(); @endphp

    <div class="container-fluid body_color">

        <div class="row">
            <div class="container">

                <div class="col-sm-6 whole_right">
                    <h2>{{ __('Verify Your Email Address') }}</h2>

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    <p>
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>


            </div>
        </div>
    </div>

@endsection
