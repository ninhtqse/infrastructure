<?php

namespace Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Api\Users\Models\User;
use Infrastructure\Libraries\ELog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // không chạy migration mặc định trong passport
        \Laravel\Passport\Passport::ignoreMigrations();
    }
}
