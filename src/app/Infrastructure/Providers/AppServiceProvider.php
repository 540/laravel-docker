<?php

namespace App\Infrastructure\Providers;

use App\Application\UserDataSource\WalletDataSource;
use App\Application\UserDataSource\WalletDataSourceFunctions;
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
        $this->app->bind(WalletDataSource::class, function () {
            return new WalletDataSourceFunctions();
        });
    }
}
