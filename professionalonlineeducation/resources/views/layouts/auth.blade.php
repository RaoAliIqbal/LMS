<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Login/Signup</title>
    <meta name="keywords" content="" />
    <meta name="description" content=" " />
    @include('layouts.partials.auth.meta')
</head>
<body>
<!--class="full-lp login-register"-->


    @include('layouts.partials.auth.header')


    @yield('content')


    @include('layouts.partials.auth.footer')





</body>
</html>