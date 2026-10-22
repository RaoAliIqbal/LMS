<?php

namespace App\Http\Controllers\Client;

use App\Models\Category;
use App\Models\State;
use App\Models\UserLicence;
use App\Notifications\NewUser;
use App\Support\StudentEnrollmentLoader;
use App\Services\PaymentService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ClientBasicProfile;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\PaymentLogs;
use Notification;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $users = User::find($user->id);
        $states = State::all();
        $paymentLogs = array_slice($this->loadPaymentHistory((int) $user->id), 0, 5);
        $courses = collect();
        $professions = collect();
        $userLinceses = collect();

        try {
            $courses = StudentEnrollmentLoader::ensureEnrollments($user, 3);
            $professionsIds = [];
            if ($users) {
                foreach ($users->courses()->with('categories')->get() as $userCourse) {
                    foreach ($userCourse->categories as $userCat) {
                        $professionsIds[] = $userCat->category_id;
                    }
                }
            }
            $userLinceses = UserLicence::where('user_id', $users->id)->limit(2)->get();
            $professions = Category::whereIn('id', array_unique($professionsIds))->get();
        } catch (\Throwable $e) {
            \Log::error('Student dashboard partial load failed: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return view('client.profileBasic', compact('users', 'states', 'courses', 'professions', 'userLinceses', 'paymentLogs'));
    }

    protected function loadPaymentHistory(int $userId, ?int $limit = null): array
    {
        try {
            $query = PaymentLogs::where('user_id', $userId)->orderByDesc('id');
            if ($limit) {
                $query->limit($limit);
            }

            return (new PaymentService())->addCourses($query->get());
        } catch (\Throwable $e) {
            \Log::error('loadPaymentHistory failed: '.$e->getMessage(), [
                'user_id' => $userId,
            ]);

            return [];
        }
    }

    public function paymentHistory(){
        $users = User::find(Auth::user()->id);
        $paymentLogs = $this->loadPaymentHistory((int) $users->id);

        return view('client.paymentHistory', compact('users', 'paymentLogs'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {  
        $service = new  ProfileService;
        $result = $service->UpdateProfile($request);
        if ($result['type'] == 'error'){
            return Redirect::to(route('client_home'))->with('error', $result['msg']);
        }else if($result['type'] == 'success'){
            return Redirect::to(route('client_home'))->with('info', $result['msg']);
        }else{
            return abort('500');
        }
    }
    public function SaveClinic(Request $request)
    {
        $service = new  ProfileService;
        $result = $service->SaveClinic($request);
        if ($result['type'] == 'error'){
            return Redirect::to(route('client_home'))->with('error', $result['msg']);
        }else if($result['type'] == 'success'){
            return Redirect::to(route('client_home'))->with('info', $result['msg']);
        }else{
            return abort('500');
        }
    }
    public function updateProfileImg(Request $request)
    {
        dd('here111');
        $service = new  ProfileService;
        $result = $service->updateProfileImg($request);
        if ($result['type'] == 'error'){
            return Redirect::to(route('client_home'))->with('error', $result['msg']);
        }else if($result['type'] == 'success'){
            return Redirect::to(route('client_home'))->with('info', $result['msg']);
        }else{
            return abort('500');
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function settings(){
        $users = User::find(Auth::user()->id);
        return view('client.profileSettings' , compact('users'));
    }

    public function updatePassword(Request $request, $id){
        $message = array(
            'current_password.required' => 'Current Password Field is Required',
            'new_password.required' => 'New Password Field is Required',
            'new_password.required_with' => 'The new password and Confirm password must match.',
            'new_password.same' => 'The new password and Confirm password must match.',
        );
        $request->validate([
            'current_password' => 'required',
            'con_password' => 'required',
            'new_password' => ['required', 'string', 'min:8', 'required_with:con_password','same:con_password'],
        ], $message);
        $service = new  ProfileService;
        $result = $service->UpdatePassword($request);
            if ($result['type'] == 'success'){
                return Redirect::to(route('client_settings'))->with('info',$result['msg']);
            }else{
                return Redirect::to(route('client_settings'))->with('error',$result['msg']);
            }
    }

    public function licenses(){
        $user = auth()->user();
        return view('client.licenses' , compact('user'));
    }
    public function licenseSave(Request $request){
        $user = auth()->user();
        $model = new UserLicence();
        if ($request->id){
            $model::where('id',$request->id)->update(['licence_no'=>$request->license_no,'renewal_data'=>$request->renewal_data,'user_id'=>$user->id]);
        }else{
            $model::create(['licence_no'=>$request->license_no,'renewal_data'=>$request->renewal_data,'user_id'=>$user->id]);
        }
        return redirect()->route('client_licenses')->with('info','License Saved');
    }
    public function licenseDelete($id){
        $user = auth()->user();
        $model = new UserLicence();
        $licence = $model::where('id',$id)->where('user_id',$user->id)->first();
        if ($licence){ $licence->delete(); return redirect()->route('client_licenses')->with('info','License deleted'); }
        return redirect()->route('client_licenses')->with('info','License not found');
    }

}
