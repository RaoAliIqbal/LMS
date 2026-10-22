@extends('layouts.web')

@section('content')
<div class="container" style="max-width:640px;padding:48px 16px;">
    <h1 style="font-size:24px;margin-bottom:16px;">Thank you</h1>
    <p style="font-size:16px;line-height:1.6;color:#333;">{{ $message }}</p>
    <p style="margin-top:24px;"><a href="{{ route('home') }}">Return to home</a></p>
</div>
@endsection
