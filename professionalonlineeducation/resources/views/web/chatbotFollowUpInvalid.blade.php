@extends('layouts.web')

@section('content')
<div class="container" style="max-width:640px;padding:48px 16px;">
    <h1 style="font-size:24px;margin-bottom:16px;">Link not found</h1>
    <p style="font-size:16px;line-height:1.6;color:#333;">This follow-up link is invalid or has expired. Please contact us through the website chat or call <a href="tel:+18889003382">1-888-900-3382</a>.</p>
    <p style="margin-top:24px;"><a href="{{ route('home') }}">Return to home</a></p>
</div>
@endsection
