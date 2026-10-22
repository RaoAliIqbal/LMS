<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\User;
use Illuminate\Support\Facades\Auth;
use DB;
use App\MessageUser;

class ProjectCountComposer
{
    public function compose(View $view)
    {
        if (Auth::id()) {
            $userId = Auth::id();


//            $allProjects = User::find($userId)->allProjects()->get();
//            $allProjects = count($allProjects);
//


            //msg




//            $view->with('projectCount', [
//                'allProjects' => $allProjects
//            ]);
        }
    }
}