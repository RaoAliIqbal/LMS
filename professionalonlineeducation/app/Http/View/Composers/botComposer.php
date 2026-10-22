<?php

namespace App\Http\View\Composers;

use App\Models\BotResponse;
use Illuminate\View\View;
use App\User;
use Illuminate\Support\Facades\Auth;
use DB;


class botComposer
{
    public function compose(View $view)
    {
        $botResponses = new BotResponse();
        $responses = $botResponses->all();

        $view->with('botComposer', [
            'responses' => $responses,
        ]);


    }
}
