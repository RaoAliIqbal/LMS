<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" href="assets/Fs/images/favicon.ico">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PSX Portal') }}</title>

    <meta name="keywords" content="" />
    <meta name="description" content=" " />

    @include('layouts.partials.courseMeta')

</head>
<body style="overflow: hidden;">


    @yield('content')



    @include('layouts.partials.CourseScripts')

    {{-- ADA Accessibility Widget --}}
    @include('layouts.partials.ada-widget')

</body>
</html>
