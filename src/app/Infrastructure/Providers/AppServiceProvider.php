<?php

namespace App\Infrastructure\Providers;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\CoinDataSource\BuyCoinDataSource;
use App\Application\UserDataSource\WalletDataSource;
use App\Application\UserDataSource\WalletDataSourceFunctions;

use App\Application\CoinDataSource\BuyCoinDataSourceFunction;
use App\Application\CoinDataSource\CryptoCoinDataSource;
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
        $this->app->bind(BuyCoinDataSource::class, function () {
            return new BuyCoinDataSourceFunction();
        });
        $this->app->bind(CoinDataSource::class, function () {
            return new CryptoCoinDataSource();
        });
        $this->app->bind(WalletDataSource::class, function () {
            return new WalletDataSourceFunctions();
        });
    }
}
