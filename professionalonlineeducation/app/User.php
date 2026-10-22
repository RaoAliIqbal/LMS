<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
//use App\Notifications\VerifyEmail;
use App\Http\Requests\ClientBasicProfile;
use Illuminate\Support\Collection;
use App\Models\Lesson;
use App\Models\UserLicence;
use App\Models\UserFiles;
use App\Models\UserClinic;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
         protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * Send the email verification notification.
     *
     * @return void
     */
//    public function sendEmailVerificationNotification()
//    {
//        $this->notify(new VerifyEmail); // my notification
//    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        if($this->first_name === null && $this->last_name === null){
            return $this->name;
        }
//        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
        return $this->first_name.' '.$this->middle_name. ' '.$this->last_name;
    }

    public function getUserDropDown($role = false, $except = false){

        if ($role == 'few'){
            if (!$except) {
                $users = $this::all();
            }else{
                $roles = \Spatie\Permission\Models\Role::all();
                $usersArray= array();
                foreach ($roles as $GetRole){
                        if(!in_array($GetRole->name,$except)){
                            $usersArray[] = $this::role($GetRole)->get();
                        }
                }
            }
        }else{
            $users = $this::all();
        }



    $data = array();
    foreach ($usersArray as $users) {
        foreach ($users as $user) {
            if ($user->first_name == null && $user->last_name == null){
                $data[$user->id] = $user->email;
            }else{
                $data[$user->id] = $user->first_name . ' ' . $user->last_name;
            }
        }
    }
    return $data;
}

    public function updateProfile($request,$id,$exceptRequest=null){

        $this::where('id',$id)->update($request->except($exceptRequest));

    }

    public function updateProfileAdmin($request,$id,$exceptRequest=null){

        $this::where('id',$id)->update($request->except($exceptRequest));

    }


    public function courses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Lesson::class,'lesson_users')->withPivot('price');
    }
    public function linceses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserLicence::class);
    }
    public function seminars(){
        return $this->belongsToMany('App\Models\Seminar','seminar_users')
                    ->withPivot('id', 'completed_at', 'enrolled_at', 'price')
                    ->withTimestamps();
    }
    public function paymentLogs(){
        return $this->hasMany('App\Models\PaymentLogs','user_id');
    }
    public function userCourseFiles($courseId): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserFiles::class,'user_id')->where('course_id', $courseId);
    }
    public function clinic(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserClinic::class,'user_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category','category_id');
    }



}
