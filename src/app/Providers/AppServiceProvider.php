<?php

namespace App\Providers;

use App\DataSource\Database\EloquentUserDataSource;
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
        $this->app->bind(EloquentUserDataSource::class, function () {
            return new EloquentUserDataSource();
        });
    }
}
