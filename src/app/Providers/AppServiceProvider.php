<?php

namespace App\Providers;

use App\DataSource\Database\EloquentUser540DataSource;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(EloquentUser540DataSource::class, function () {
            return new EloquentUser540DataSource();
        });
    }
}
