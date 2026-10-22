<?php

namespace App\Http\Controllers;

use App\Instructor;
use App\Mail\InstructorRegistrationMail;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Profession;
use App\Models\State;
use App\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    public function showRegistrationForm(){
        $data = array(
            'timezones'     => Timezone::where('id', '>', 59)->get(),
            'catalogs'      => Category::latest()->get(),
            'states'        => State::get(),
            'professions'   => Profession::get(),
        );
        return view('instructor.registration')->with($data);
    }

    public function store(Request $req){
        $req->validate([
            'first_name'                                => ['required', 'string', 'max:50'],
            'last_name'                                 => ['required', 'string', 'max:50'],
            'email'                                     => ['required', 'string', 'max:50', Rule::unique('instructors')->ignore(base64_decode($req->instructor_id))],
            'office_email'                              => ['nullable', 'string', 'max:50', Rule::unique('instructors')->ignore(base64_decode($req->instructor_id))],
            'password'                                  => ['nullable', 'string', 'max:50', 'confirmed', Rule::requiredIf(!isset($req->instructor_id))],
            'titles'                                    => ['array'],
            'catalogs'                                  => ['array'],
            'catalgos.*'                                => ['required', 'string'],
            'titles.*'                                  => ['required', 'string', 'max:50'],
            'phone_no'                                  => ['required', 'string', 'max:50'],
            'office_phone_no'                           => ['nullable', 'string', 'max:50'],
            'address'                                   => ['required', 'string', 'max:5000'],
            'city'                                      => ['required', 'string', 'max:250'],
            'state'                                     => ['required', 'string', 'max:200'],
            'zip_code'                                  => ['required', 'string', 'max:100'],
            'is_license'                                => ['required', 'in:yes,no'],
            'license_no'                                => ['array', 'nullable'],
            'license_no.*'                              => ['nullable', 'required_if:is_license,yes', 'string', 'max:50'],
            'license_verification_date'                 => ['array', 'nullable'],
            'license_verification_date.*'               => ['nullable', 'date'],
            'state_verified_date'                       => ['nullable', 'date'],
            'contract_received_signed_date'             => ['nullable', 'date'],
            'contract_term_date'                        => ['nullable', 'date'],
            'w_received_date'                           => ['nullable', 'date'],
            'email_setup_date'                          => ['nullable', 'date'],
            // 'background'                                => ['required', 'string', 'max:50000'],
            'bio'                                       => ['required', 'string', 'max:50000'],
            'days'                                      => ['nullable', 'array'],
            'office_start_time'                         => ['nullable', 'array'],
            'office_end_time'                           => ['nullable', 'array'],
            'revenue_percentage'                        => ['nullable', 'numeric', 'min:1', 'max:99'],
            // 'photo'                                     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048', Rule::requiredIf(!isset($req->instructor_id))],
            // 'w9_form'                                   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048', Rule::requiredIf(!isset($req->instructor_id))],
            // 'resume'                                    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048', Rule::requiredIf(!isset($req->instructor_id))],
            // 'instructor_information'                    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048', Rule::requiredIf(!isset($req->instructor_id))],
            'photo'                                     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048'],
            'w9_form'                                   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048'],
            'resume'                                    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048'],
            'instructor_information'                    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:2048'],

            'timezone'                                  => ['required'],
            'instructor_id'                             => ['nullable'],
            'is_profile'                                => ['nullable'],
            'profession'                                => ['required', 'string', 'max:250'],
            'state_id'                                  => ['array'],
            'state_id.*'                                => ['nullable'],
            'is_add_course'                             => ['nullable', 'in:0,1'],
            'checkbox_arr'                              => ['nullable']
        ]);

        if($req->has('photo')){
            $photo = $this->manageAttachments($req,'photo','photo','photo');
        }
        if($req->has('w9_form')){
            $w9_form = $this->manageAttachments($req,'instructors','w9_form','w9_form');
        }
        if($req->has('resume')){
            $resume = $this->manageAttachments($req,'resume','resume','resume');
        }
        if($req->has('instructor_information')){
            $instructor_information = $this->manageAttachments($req,'instructor_information','instructor_information','instructor_information');
        }
        $checkbox_arr = [];
        if(!empty($req->checkbox_arr)){
            foreach($req->checkbox_arr AS $index=>$arr){
                $checkbox_arr[$index] = true; 
            }
        }
        
        $arr = array(
            'first_name'                  => $req->first_name,
            'last_name'                   => $req->last_name,
            'email'                       => $req->email,
            'office_email'                => $req->office_email,
            'password'                    => $req->password,
            'real_password'               => $req->password,
            'phone_no'                    => $req->phone_no,
            'office_phone_no'             => $req->office_phone_no,
            'address'                     => $req->address,
            'city'                        => $req->city,
            'state'                       => $req->state,
            'zip_code'                    => $req->zip_code,
            // 'license_no'                  => $req->license_no,
            'state_verified_date'         => $req->state_verified_date,
            'contract_received_signed_date'         => $req->contract_received_signed_date,
            'contract_term_date'         => $req->contract_term_date,
            'w_received_date'            => $req->w_received_date,
            'email_setup_date'           => $req->email_setup_date,
            // 'background'                 => $req->background,
            'bio'                        => $req->bio,
            'timezone_id'                   => $req->timezone,
            // 'office_start_time'          => $req->office_start_time,
            // 'office_end_time'            => $req->office_end_time,
            // 'revenue_percentage'         => (!empty($req->revenue_percentage)) ? $req->revenue_percentage : '',
            'photo'                      => @$photo,
            'w9_form'                    => @$w9_form,
            'resume'        => @$resume,
            'instructor_information'     => @$instructor_information,
            'created_at'                 => now(),
            'updated_at'                 => now(),
            'profession'                 => $req->profession,
            'is_license'                 => $req->is_license,
            'is_add_course'              => $req->is_add_course,
            'checkbox_arr'               => json_encode($checkbox_arr)
        );

        (!empty($req->revenue_percentage)) ? $arr['revenue_percentage'] = $req->revenue_percentage : '';
    
        if(isset($req->instructor_id) && !empty($req->instructor_id)){
            $instructor = Instructor::findOrFail(base64_decode($req->instructor_id));
            $msg        = 'Instructor updated successfully';

            if (!isset($req->password)) unset($arr['password']);
            if (!isset($req->photo)) unset($arr['photo']);
            if (!isset($req->w9_form)) unset($arr['w9_form']);
            if (!isset($req->resume)) unset($arr['resume']);
            if (!isset($req->instructor_information)) unset($arr['instructor_information']);

        }else{
            $instructor = new Instructor();
            $msg        = 'Instructor has created successfully';
        }

        $instructor->fill($arr);
        $instructor->save();
            
        $title_arr = collect($req->title)->map(function ($title) {
            return ['title' => $title];
        })->toArray();

        if($req->is_license == 'yes'){
            $license_arr = collect($req->license_no)->map(function ($title,$index)use($req) {
                return ['license_no' => $title, 'verification_date'=>$req->license_verification_date[$index] ?? NULL, 'state_id'=>$req->state_id[$index] ?? NULL];
            })->toArray();
        }
        // dd($license_arr);
        
        $instructor_office_timing_arr = collect($req->days)->map(function($day, $key) use ($req){
            // if(isset($req->office_start_time[$key]) && isset($req->office_end_time[$key]) && $key == 0){
            //     return ['day'=>'Monday', 'start_time'=>$req->office_start_time[$key], 'end_time'=> $req->office_end_time[$key]];
            // }elseif(isset($req->office_start_time[$key]) && isset($req->office_end_time[$key]) && $key == 1){
            //     return ['day'=>'Tuesday', 'start_time'=>$req->office_start_time[$key], 'end_time'=> $req->office_end_time[$key]];
            // }
  
                switch ($day) {
                    case 'Monday':
                        return ['day' => 'Monday', 'start_time' => $req->office_start_time[0], 'end_time' => $req->office_end_time[0]];
                    case 'Tuesday':
                        return ['day' => 'Tuesday', 'start_time' => $req->office_start_time[1], 'end_time' => $req->office_end_time[1]];
                    case 'Wednesday':
                        return ['day' => 'Wednesday', 'start_time' => $req->office_start_time[2], 'end_time' => $req->office_end_time[2]];
                    case 'Thursday':
                        return ['day' => 'Thursday', 'start_time' => $req->office_start_time[3], 'end_time' => $req->office_end_time[3]];
                    case 'Friday':
                        return ['day' => 'Friday', 'start_time' => $req->office_start_time[4], 'end_time' => $req->office_end_time[4]];
                    case 'Saturday':
                        return ['day' => 'Saturday', 'start_time' => $req->office_start_time[5], 'end_time' => $req->office_end_time[5]];
                    case 'Sunday':
                        return ['day' => 'Sunday', 'start_time' => $req->office_start_time[6], 'end_time' => $req->office_end_time[6]];
                    default:
                        return null; // Handle any other cases here
                }
            
        })->filter();
        @$instructor->titles()->delete();
        $instructor->titles()->createMany($title_arr);
        @$instructor->officeTimings()->delete();
        $instructor->officeTimings()->createMany($instructor_office_timing_arr);

        if($req->is_license == 'yes'){
            @$instructor->licenses()->delete();
            $instructor->licenses()->createMany($license_arr);
        }
        
        (!empty($req->catalogs)) ? $instructor->catalogs()->sync($req->catalogs) : '';
        
        (!isset($req->instructor_id)) ? Mail::to($req->email)->send(new InstructorRegistrationMail) : '';
       
        (Profession::where('name', $req->profession)->doesntExist()) ? Profession::create(['name'=>$req->profession])  : '';
        (State::where('name', $req->state)->doesntExist()) ? State::create(['name'=>$req->state])  : '';
        
        if(isset($req->is_admin) && $req->is_admin == 1){
            return redirect()->route('admin.instructors.index')->with('success',$msg);
        }elseif(isset($req->is_profile) && $req->is_profile == 1){
            return redirect()->back()->with('success', 'Profile updated successfully');

        }

        return redirect()->back()->with('success', 'You have registered successfully');
        
    }

    public function manageAttachments($request,$folder,$fileName,$requestName){

        if ($file = $request->file($fileName)) {

            $memeType = $file->getMimeType();
            $path = "uploads/".$folder;
            if(!is_dir($path)){
                if (!mkdir($path, 0777, true) && !is_dir($path)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
                }
            }
            $name = rand(0,9999).$file->getClientOriginalName();
            $file->move($path, $name);
            $request->request->add([$requestName => $path.'/'.$name]);
        }
        return $path.'/'.$name;
    }

    public function loginForm(){
        return view('instructor.login');
    }

    public function login(Request $req){
        
        $req->validate([
            'email'     => ['required', 'email', 'email', 'max:100'],
            'password'  => ['required', 'string', 'max:100']
        ]);
        if(auth()->guard('instructor')->attempt($req->only('email', 'password'))){
            if(auth('instructor')->user()->status == 0){
                auth('instructor')->logout();
                return redirect()->back()->withInput()->with('error', 'Your account is not approved wait for the admin approval');
            }
            return redirect()->route('admin_dashboard');
        }else{
            return redirect()->back()->withInput()->with('error', 'credentials are incorrect');
        }
    }
}
