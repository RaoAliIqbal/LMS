<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="keywords" content="" />
    <meta name="description" content=" " />

    @include('layouts.partials.meta')

</head>
<body>
<header>
    @include('layouts.partials.header')
</header>
<section class="main_wrapper">

    <div class="container">

        <div class="row page_titles">
            <div class="col-sm-8">
                <h1>@yield('pageTitle')</h1>
            </div>
            <div class="col-sm-4">

            </div>
        </div>

        <div class="row">

            <div class="col-sm-3">
                @include('layouts.partials.sideBar')
            </div>

            <div class="col-sm-9">
                @yield('content')
            </div>
        </div>
    </div>

</section>


@include('layouts.partials.footer')

{{-- ADA Accessibility Widget --}}
@include('layouts.partials.ada-widget')

</body>
</html>
