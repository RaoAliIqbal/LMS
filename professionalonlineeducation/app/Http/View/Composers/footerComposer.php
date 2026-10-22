<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\User;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Services\BlogService;
use App\Services\EducationService;

class footerComposer
{
    public function compose(View $view)
    {
        $service = new BlogService();
        $eduService = new EducationService();
        $recentPosts = $service->recentPosts(4);
        $recentLessons = $eduService->recentLesson(4);

        $view->with('footerData', [
            'recentPosts' => $recentPosts,
            'recentLessons' => $recentLessons
        ]);


    }
}