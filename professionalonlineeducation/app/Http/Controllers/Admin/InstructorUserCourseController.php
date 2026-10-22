<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\InstructorUserCourse;
use Illuminate\Http\Request;

class InstructorUserCourseController extends Controller
{
    public function index(){
        $data = array(
            'instructor_users'     => InstructorUserCourse::with(['instructor', 'user', 'course'])->get(),
        );
        return view('admin.instructor_user_courses.index')->with($data);
    }
}
