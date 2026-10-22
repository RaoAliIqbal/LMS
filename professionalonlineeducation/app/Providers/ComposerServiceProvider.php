<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use  View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // botComposer removed — chatbot replaced with new implementation
    }
}
