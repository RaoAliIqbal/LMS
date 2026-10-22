<div class="row">
    @include('layouts.partials.loginForm')
    @include('layouts.partials.alertBox')

    @if (Route::has('password.request'))

        <li>Forgot Password? <a href="{{ route('password.request') }}" >Click Here!</a></li>

    @endif
    <li>Don't Have An Account? <a href="{{ route('register') }}">Register</a></li>
    <form method="POST" action="{{ route('login') }}">
        @csrf
<ul>
    <li class="col-lg-12">
        <div class="form_input_field form_input_field01 login_email">
            <input type="email" name="email" value="{{ old('email') }}" required  id="email" placeholder="Email">
            <label for="email">Email</label>
        </div>
    </li>
    <li class="col-lg-12">
        <div class="form_input_field form_input_field01 login_password">
            <input type="password" name="password" required id="password" placeholder="Password">
            <label for="password">Password</label>
        </div>
    </li>
    <li class="col-lg-12">
        <input type="submit" value="Login">
    </li>
</ul>
    </form>

</div>
