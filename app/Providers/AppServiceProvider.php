<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illumintate\SUpport\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(){
        if(config("app.env") != "local"){
            URL::forceScheme("https");
        }
    }
}


