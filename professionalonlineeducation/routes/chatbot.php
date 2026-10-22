<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Support chatbot (public) — loaded from RouteServiceProvider with "web" middleware.
| Deploy this file on live + register the group in RouteServiceProvider::mapWebRoutes().
|--------------------------------------------------------------------------
*/

Route::post('/chatbot/escalate', 'WebsiteController@chatbotEscalate')
    ->middleware('throttle:20,1')
    ->name('chatbot.escalate');

// GET renders a confirmation page only (no DB write, no mail) to defeat email-client prefetch
// / Outlook Safe Links / Gmail proxy auto-click. The POST below performs the actual mutation.
Route::get('/chatbot/followup/{token}/{outcome}', 'WebsiteController@chatbotFollowUp')
    ->middleware('throttle:30,1')
    ->name('chatbot.followup');

Route::post('/chatbot/followup/{token}/{outcome}', 'WebsiteController@chatbotFollowUpConfirm')
    ->middleware('throttle:30,1')
    ->name('chatbot.followup.confirm');

Route::get('/chatbot/qa.json', 'WebsiteController@chatbotQaJson')
    ->middleware('throttle:60,1')
    ->name('chatbot.qa.json');
