<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Show the Arizona Chiropractic Assistant landing page.
     */
    public function arizonaChiropracticAssistant()
    {
        return view('pages.arizona-chiropractic-assistant');
    }
}