<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\State;
use App\Models\UserClinic;
use App\Models\UserLicence;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\General\Helper;
use App\Notifications\NewUser;
use App\Notifications\NewUserAdmin;
use App\Rules\Recaptcha;
use Notification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
//    protected $redirectTo = '/user/dashboard/licenses';
    protected $redirectTo = '/user/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {       
        if (substr(url()->previous(),-8) == 'checkout'){
            session(['url.intended' => url()->previous()]);
        }
        $states = State::all();
        $categories = Category::orderBy('sort_by','ASC')->get();
        return view('auth.register',compact('states','categories'));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
   
        return Validator::make($data, [
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'category_id'       => ['required',],
            'phone'             => ['required', 'string', 'max:255'],
            'state'             => ['required', 'string', 'max:255'],
            'zipcode'           => ['required', 'string', 'max:255'],
            'office_name'       => ['required', 'string', 'max:255'],
            'office_phone'      => ['required', 'string', 'max:255'],
            'city'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'office_address'    => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9\s\#\-\.\,\']+$/u'],
            'address'           => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9\s\#\-\.\,\']+$/u'],
            'office_state'             => ['required', 'string', 'max:100'],
            'office_zipcode'           => ['required', 'string', 'max:50'],
            'office_city'              => ['required', 'string', 'max:100'],
            'suite'                    => ['nullable']
            // 'g-recaptcha-response' => new Recaptcha(),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    
    protected function create(array $data)
    {   
        $user = User::create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? '',
            'last_name' => $data['last_name'],
            'name' => trim($data['first_name'].' '.($data['middle_name'] ?? '').' '.$data['last_name']),
            'phone' => $data['phone'],
            'address' => $data['address'],
            'email' => $data['email'],
            'state' => $data['state'],
            'city' => $data['city'],
            'zipcode' => $data['zipcode'],
            'category_id' => $data['category_id'],
            'password' => Hash::make($data['password']),
            'real_password' => $data['password'],
            'intended_url'=> isset($data['intended_url']) ? $data['intended_url'] : '/',
            'suite'         => $data['suite'] ?? null
        ]);

        try {
            $user->assignRole(2);
        } catch (\Throwable $e) {
            \Log::error('Registration assignRole failed: '.$e->getMessage(), ['user_id' => $user->id]);
        }

        if(isset($data['licence_no']) && $data['licence_no'] != null && $data['renewal_data'] != null){
            UserLicence::create(['user_id'=>$user->id,'licence_no'=>$data['licence_no'],'renewal_data'=>$data['renewal_data']]);
        }

        UserClinic::create([
            'user_id'       =>$user->id,
            'clinic_name'   =>$data['office_name'],
            'office_phone'  =>$data['office_phone'], 
            'address'       =>$data['office_address'],
            'city'          =>$data['office_city'],
            'state'         =>$data['office_state'],
            'zipcode'       =>$data['office_zipcode']
        ]);

        Helper::sendNotificationSafely($user, new NewUser($user), 'registration welcome');
        Helper::sendNotificationSafely(User::find(4), new NewUserAdmin($user), 'registration admin');

        return $user;
    }

    public function register(Request $request)
    {
        try {
            return $this->registerUser($request);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error('Registration failed: '.$e->getMessage(), [
                'email' => $request->input('email'),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Registration could not be completed. Please try again or call 1-888-900-3382.');
        }
    }

    /**
     * @see RegistersUsers::register()
     */
    protected function registerUser(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new \Illuminate\Http\JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        if ($redirect = Helper::redirectAfterPostLoginSeminarIfAny($user)) {
            return $redirect;
        }
    }
}
