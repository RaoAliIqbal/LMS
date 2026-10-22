@extends('layouts.web')

@section('content')
<div class="container" style="max-width:640px;padding:48px 16px;">
    <h1 style="font-size:24px;margin-bottom:16px;">Already recorded</h1>
    <p style="font-size:16px;line-height:1.6;color:#333;">We’ve already received your response for this follow-up. If you need more help, please use the chat on our website or call <a href="tel:+18889003382">1-888-900-3382</a>.</p>
    <p style="margin-top:24px;"><a href="{{ route('home') }}">Return to home</a></p>
</div>
@endsection
