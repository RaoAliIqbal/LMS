<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Sign in</title>
    <meta name="description" content="Responsive, Bootstrap, BS4"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- for ios 7 style, multi-resolution icon of 152x152 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ route('admin.assets') }}/images/logo.svg">
    <meta name="apple-mobile-web-app-title" content="Flatkit">
    <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="shortcut icon" sizes="196x196" href="{{ route('admin.assets') }}/images/logo.svg">
    <!-- style -->
    <link rel="stylesheet" href="{{ route('admin.assets') }}/libs/font-awesome/css/font-awesome.min.css"
          type="text/css"/>
    <!-- build:css ../assets/css/app.min.css -->
    <link rel="stylesheet" href="{{ route('admin.assets') }}/libs/bootstrap/dist/css/bootstrap.min.css"
          type="text/css"/>
    <link rel="stylesheet" href="{{ route('admin.assets') }}/css/app.css" type="text/css"/>
    <link rel="stylesheet" href="{{ route('admin.assets') }}/css/style.css" type="text/css"/>
    <!-- endbuild -->
</head>
<body>
<div class="d-flex flex-column flex">
    <div class="navbar light bg pos-rlt box-shadow">
        <div class="mx-auto">
            <!-- brand -->
            <a href="{{ route('home') }}" class="navbar-brand">

                <img src="{{ asset('admin-assets/images/logo.png') }}"
                     alt=".">

            </a>
            <!-- / brand -->
        </div>
    </div>
    <div id="content-body">
        <div class="py-5 text-center w-100">
            <div class="mx-auto w-xxl w-auto-xs">
                <div class="px-3">

                    <form method="POST" action="{{ route('login') }}" id="loginform">
                        @csrf
                        @if(session('failed'))
                            <div class="alert alert-danger">{{ session('failed') }}</div>
                        @endif

                        <div class="form-group">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="Email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password" required
                                   autocomplete="current-password" placeholder="Password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>

                        <button type="submit" class="btn primary">Sign in</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->

<!-- Bootstrap -->
<script src="{{ route('admin.assets') }}/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="{{ route('admin.assets') }}/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- core -->
<script src="{{ route('admin.assets') }}/libs/pace-progress/pace.min.js"></script>
<script src="{{ route('admin.assets') }}/libs/pjax/pjax.min.js"></script>
<script src="{{ route('admin.assets') }}/js/lazyload.config.js"></script>
<script src="{{ route('admin.assets') }}/js/lazyload.js"></script>
<script src="{{ route('admin.assets') }}/js/plugin.js"></script>
<script src="{{ route('admin.assets') }}/js/nav.js"></script>
<script src="{{ route('admin.assets') }}/js/scrollto.js"></script>
<script src="{{ route('admin.assets') }}/js/toggleclass.js"></script>
<script src="{{ route('admin.assets') }}/js/theme.js"></script>
<script src="{{ route('admin.assets') }}/js/app.js"></script>
<!-- endbuild -->

</body>
</html>
