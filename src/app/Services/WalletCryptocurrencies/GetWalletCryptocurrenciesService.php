<?php


namespace App\Services\WalletCryptocurrencies;


use App\DataSource\Database\EloquentWalletCoinDataSource;
use Exception;

class GetWalletCryptocurrenciesService
{
    private EloquentWalletCoinDataSource $eloquentWalletDataSource;

    public function __construct(EloquentWalletCoinDataSource $eloquentWalletCoinDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentWalletCoinDataSource;
    }


    public function execute($walletId): array
    {
        $walletCoins = $this->eloquentWalletDataSource->findWalletCoins($walletId);
        return [
            'coin_id' => 1,
            'name' => 'Bitcoin',
            'symbol' => 'BTC',
            'amount' => 1,
            'value_usd' => 1
        ];
    }
}
