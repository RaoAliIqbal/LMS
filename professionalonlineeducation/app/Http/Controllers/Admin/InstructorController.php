<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InstructorController as ControllersInstructorController;
use App\Instructor;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Profession;
use App\Models\State;
use App\Timezone;
use Illuminate\Http\Request;

class InstructorController extends ControllersInstructorController
{
    public function index(){
        $data = array(
            'instructors'   => Instructor::latest()->get(),
        );
        return view('admin.instructor.index')->with($data);
    }

    public function add(){
        $data = array(
            'timezones' => Timezone::get(),
            'catalogs'  => Category::latest()->get(),
            'states'    => State::get(),
            'professions'   => Profession::get(),
        );
        return view('admin.instructor.add')->with($data);
    }

    public function updateStatus($id, $status){
        if(in_array($status, [1,0])){
            Instructor::where('id', base64_decode($id))->update(['status'=>$status]);
            return redirect()->route('admin.instructors.index')->with('info', 'Status updated successfully');
        }
        abort(404);
    }

    public function edit($id){
        $data = array(
            'timezones'         => Timezone::get(),
            'catalogs'          => Category::latest()->get(),
            'edit_instructor'   => Instructor::findOrFail(base64_decode($id)),
            'is_update'         => true,
            'states'    => State::get(),
            'professions'   => Profession::get(),
            'checkbox_arr'  => self::checboxArr()
        );
        // dd($data['edit_instructor']->officeTimings[0]->day);
        return view('admin.instructor.add')->with($data);
    }

    public function checboxArr(){
        return [
            'contact_info'              => 'Contact Info',
            'temp_password'             => 'Temp. Password',
            'curriculum_vitae'          => 'Curriculum Vitae',
            'drivers_license'           => 'Drivers License',
            'professional_license'      => 'Professional License',
            'tax_id_ss'                 => 'Tax ID / SS #',
            'signed_w_9'                => 'Signed W-9',
            'signed_1_9_w_id'           => 'Signed 1-9 w/ID',
            '1_9'                       => '1-9',
            'background_release'        => 'Background Release',
            'background_check'          => 'Background Check',
            'signed_contract'           => 'Signed Contract',
            'copy_of_e_o_ins'           => 'Copy of E & O Ins',
            'signed_policies'           => 'Signed Policies',
            'marketing_release'         => 'Marketing Release',
            'instructor_picture'        => 'Instructor Picture',
            'website_bio'               => 'Website Bio',
            'course_description'        => 'Course description',
            'outline_paginated'         => 'Outline (paginated)',
            'course_mod_setup'          => 'Course Mod. set up',
            'exercises_and_answers'     => 'Exercises & answers',
            'test_q_and_a'              => 'Test Q & A',
            'course_pictures'           => 'Course Pictures',
            'course_upload'             => 'Course Upload',
            'course_audited'            => 'Course Audited',
            'course_guidance_instructions'=> 'Course guidance instructions',
            'how_to_change_password'      => 'How to change a password',
            'how_to_download_forms'       => 'How to download forms',
            'how_to_upload_forms'         => 'How to upload forms',
            'how_to_contact_admin'        => 'How to contact admin',
            'how_to_set_up_office_hours'  => 'How to set up office hours',
            'how_students_login'          => 'How students login',
            'how_chat_bot_works'          => 'How chat bot works',
            'how_to_upload_course'        => 'How to upload a course',
            'how_to_edit_course'          => 'How to edit a course',
            'how_to_update_test_q_and_a'  => 'How to update test Q and A'

        ];
    }
}
