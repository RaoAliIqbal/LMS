<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Instructor;
use App\Models\Lesson;
use Exception;
use Illuminate\Http\Request;

class InstructorCourseController extends Controller
{
    public function index(){
        $data = array(
            'instructors' => Instructor::with(['courses:id,title'])->latest()->get(['id', 'first_name', 'last_name']),
            'courses'     => Lesson::get(['id', 'title']),
        );
        return view('admin.instructor_courses.index')->with($data);
    }

    public function store(Request $req){
        $req->validate([
            'instructor_id'     => ['array'],
            'instructor_id.*'   => ['required'],
            'course_id'         => ['required']
        ]);
        
        $error_arr              = [];//to store those instructor who already had this course
        
        foreach($req->instructor_id AS $instructor){
            $instructor_arr = self::storeInstructorCourse($instructor, $req->course_id);
            
            if($instructor_arr !== true) $error_arr[] = $instructor_arr;//store those instructors who has already this course
        }

        return (empty($error_arr))//return response
        ? redirect()->back()->with('success', 'Instructor course added successfully')
        : redirect()->back()->withErrors($error_arr)->with('error', 'These users already have this course.');
    }

    public function storeInstructorCourse($instructor_id, $course_id){//store instructor courses
        
        try{
            $instructor = Instructor::findOrFail($instructor_id);//find the instructor
        
            if(!$instructor->courses->contains($course_id)){//check if instructor already had this course
                $instructor->courses()->attach($course_id);//if not then store 
                return true;
            }
            return $instructor->first_name.' '. $instructor->last_name;//if yes then return error
        }catch(Exception $e){
            return ['error'=>'Error attaching coruse'];
        }
    }

    public function delete($instructor_id, $course_id){//delete instructor course
        try{
            $instructor = Instructor::findOrFail($instructor_id);
            $instructor->courses()->detach($course_id);
            return redirect()->back()->with('success', 'Instructor course has been removed');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Some erro occured');
        }
    }
}
