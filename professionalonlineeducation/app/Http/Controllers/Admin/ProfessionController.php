<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ProfessionController extends Controller
{
    public function index(){
        $data = array(
            'professions'   => Profession::orderBy('id', 'DESC')->get(),
        );
        return view('admin.profession.index')->with($data);
    }

    public function store(Request $req){//add and update the coupon codes
        $req->validate([
            'profession'      => ['required', 'string', 'min:1', 'max:50', 'alpha_dash', Rule::unique('professions', 'name')->ignore(@base64_decode($req->profession_id))],
            'profession_id' => ['nullable']
        ]);
        if(isset($req->profession_id) && !empty($req->profession_id)){
            $profession = Profession::findOrFail(base64_decode($req->profession_id));
            $msg    = 'Profession updated successfully';
        }else{
            $profession = new Profession();
            $msg    = 'Profession added successfully';
        }
        $profession->name    = $req->profession;
        $profession->save();

        return redirect()->route('admin.professions.index')->with('info', $msg);
    }

    public function edit($id){//edit copon
        if(isset($id) && !empty($id)){
            $data = array(
                'edit_profession'   => Profession::findOrFail(base64_decode($id)),
                'professions'   => Profession::orderBy('id', 'DESC')->get(),
                'is_update'     => true
            );
            return view('admin.profession.index')->with($data);
        }
        abort(404);
    }

    public function delete($id){
        Profession::destroy(base64_decode($id));
        return redirect()->route('admin.professions.index')->with('info', 'Coupon deleted successfully');
    }

    public function updateStatus($id, $status){
        if(in_array($status, [1,0])){
            Profession::where('id', base64_decode($id))->update(['status'=>$status]);
            return redirect()->route('admin.professions.index')->with('info', 'Status updated successfully');
        }
        abort(404);
    }
}
