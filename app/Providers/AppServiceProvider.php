<?php

namespace App\Providers;

use View;
use App\Models\BasicSetting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //register
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $basic = BasicSetting::first();
        View::share('basic', $basic);
    }
}
