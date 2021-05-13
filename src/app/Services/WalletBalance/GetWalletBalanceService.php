<?php

namespace App\Services\WalletBalance;

use App\DataSource\API\CoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;

class GetWalletBalanceService
{
    private EloquentWalletDataSource $eloquentWalletDataSource;
    private CoinDataSource $coinDataSource;

    public function __construct(EloquentWalletDataSource $eloquentDataSource, CoinDataSource $coinDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentDataSource;
        $this->coinDataSource = $coinDataSource;
    }

    public function execute($walletId):float
    {
        $wallet = $this->eloquentWalletDataSource->findWalletById($walletId);

        $pastPrice = 0;
        $actualPrice = 0;

        foreach ($wallet->coins as $coin){
            $pastPrice += $coin->amount * $coin->value_usd;
            $actualCoin = $this->coinDataSource->findCoinById($coin->coin_id);
            $actualPrice += $coin->amount * $actualCoin['price_usd'];
        }

        return $actualPrice - $pastPrice;
    }
}
