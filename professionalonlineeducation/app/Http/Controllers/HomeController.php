<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Project;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $roles = $user->getRoleNames();
        return redirect('/'.strtolower($roles[0]).'/dashboard');

    }

    public function logout(Request $req){
        Auth::logout();
        if(isset($req->show_message)){
            return redirect(route('login'))->with('error', 'You have been logout forefully for not performing any activity');
        }
        return redirect(route('login'));
    }


    public function getPeopleByProject(Request $request){
        $user = User::find(Auth::user()->id);
        $project_id = $request->projectId;
        $ProjectModel = new Project();
        $peoples = $ProjectModel::withTrashed()->find($project_id)->users->pluck('first_name','id');

        unset($peoples[Auth::user()->id]);
        return json_encode($peoples);


    }
}
