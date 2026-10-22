<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\General\Helper;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        if (substr(url()->previous(),-8) == 'checkout'){
            session(['url.intended' => url()->previous()]);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            return $this->traitLogin($request);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error('Login failed: '.$e->getMessage(), [
                'email' => $request->input('email'),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->with('error', 'Sign in could not be completed. Please try again or call 1-888-900-3382.');
        }
    }

    /**
     * @see AuthenticatesUsers::login()
     */
    protected function traitLogin(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function authenticated($request, $user){
        try {
            if ($user->status == 0){
                Auth::logout();
                return redirect('/login')->with('error','Your account is temporary disabled.');
            }

            if ($redirect = Helper::redirectAfterPostLoginSeminarIfAny($user)) {
                return $redirect;
            }

            // Staff/admin must never land on student routes (e.g. saved url.intended).
            if ($user->hasRole('Admin') || session()->pull('staff_login')) {
                session()->forget('url.intended');
                return redirect()->route('admin_dashboard');
            }

            if (session()->has('url.intended')) {
                return redirect(session()->pull('url.intended'));
            }

            if ($user->hasRole('User')) {
                return redirect()->route('client_profile');
            }

            $roles = \Spatie\Permission\Models\Role::all();
            foreach ($roles as $role){
                if($user->hasRole($role->name)){
                    $roleGroup = strtolower($role->name);
                    return redirect('/'.$roleGroup.'/dashboard');
                }
            }

            return redirect(RouteServiceProvider::HOME);
        } catch (\Throwable $e) {
            \Log::error('Login redirect failed: ' . $e->getMessage(), ['user_id' => $user->id]);
            if ($user->hasRole('Admin')) {
                return redirect()->route('admin_dashboard');
            }
            return redirect(RouteServiceProvider::HOME);
        }
    }

    public function LoginRegister(){
        return abort('404');
    }
}
