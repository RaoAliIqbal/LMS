<div id="login" class="modal fade toolform login" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="login_form">
                <h2>LOGIN</h2>

                    @include('layouts.partials.loginForm')

                <div class="Forgotdiv">
                    <ul>
                        <li>Forgot Password? <a href="#forgot_password" data-dismiss="modal" aria-hidden="true" data-toggle="modal">Click Here!</a></li>
                        <li>Don't Have An Account? <a href="#register_account" data-dismiss="modal" data-toggle="modal">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="forgot_password" class="modal fade toolform login" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="login_form">
                <h2>LOGIN</h2>
                <div class="row">
                    <ul>
                        <li class="col-lg-12">
                            <div class="form_input_field form_input_field01 login_email">
                                <input type="email" name="email" id="email" placeholder="Email">
                                <label for="email">Email</label>
                            </div>
                        </li>
                        <li class="col-lg-12">
                            <input type="submit" value="Email">
                        </li>
                    </ul>
                </div>
                <div class="Forgotdiv">
                    <ul>
                        <li>Back to Login? <a href="#login" data-dismiss="modal" aria-hidden="true" data-toggle="modal">Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="register_account" class="modal fade toolform login" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="login_form">
                <img src="{{ url('assets/images/logo-new.png') }}" alt="">
                <h2>CREATE AN ACCOUNT</h2>
                <div class="row">
                    @include('layouts.partials.alertBox')
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                    <ul>
                        <li class="col-lg-6">
                            <div class="form_input_field form_input_field01 login_email">
                                <input type="text" value="{{ old('name') }}" required name="name" id="email" placeholder="Full Name">
                                <label for="email">Full Name</label>
                            </div>
                        </li>
                        <li class="col-lg-6">
                            <div class="form_input_field form_input_field01 login_email">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Email">
                                <label for="email">Email</label>
                            </div>
                        </li>
                        <li class="col-lg-6">
                            <div class="form_input_field form_input_field01 login_password">
                                <input type="password" name="password" required id="password" placeholder="Password">
                                <label for="password">Password</label>
                            </div>
                        </li>
                        <li class="col-lg-6">
                            <div class="form_input_field form_input_field01 login_password">
                                <input type="password" name="password_confirmation" required id="password" placeholder="Password">
                                <label for="password">Password (confirm)</label>
                            </div>
                        </li>
                        <li class="col-lg-12">
                            <input type="submit" value="CREATE AN ACCOUNT">
                        </li>
                    </ul>
                    </form>
                </div>
                <div class="Forgotdiv">
                    <ul>
                        <li>Already hava an account? <a href="#login" data-dismiss="modal" aria-hidden="true" data-toggle="modal">Click Here!</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
