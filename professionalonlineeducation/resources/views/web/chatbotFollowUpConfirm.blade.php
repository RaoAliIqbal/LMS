@extends('layouts.web')

@section('content')
<div class="container" style="max-width:640px;padding:48px 16px;">
    <h1 style="font-size:24px;margin-bottom:16px;">Confirm your response</h1>

    <p style="font-size:16px;line-height:1.6;color:#333;">
        You are about to record the following response to our follow-up email:
        <strong>{{ $outcomeLabel }}</strong>.
    </p>

    <p style="font-size:14px;line-height:1.6;color:#555;">
        Please confirm so we can update your support request. You only need to do this once.
    </p>

    <form method="POST"
          action="{{ route('chatbot.followup.confirm', ['token' => $token, 'outcome' => $outcome]) }}"
          style="margin-top:24px;">
        @csrf
        <button type="submit"
                aria-label="Confirm response: {{ $outcomeLabel }}"
                style="background:#1379ba;color:#fff;border:0;padding:12px 28px;border-radius:6px;font-weight:bold;font-size:14px;cursor:pointer;">
            Confirm response
        </button>
    </form>

    <p style="margin-top:24px;font-size:13px;color:#888;line-height:1.6;">
        If you did not mean to open this link, you can safely close this page &mdash; nothing is recorded until you press <strong>Confirm response</strong>.
    </p>

    <p style="margin-top:24px;"><a href="{{ route('home') }}">Return to home</a></p>
</div>
@endsection
