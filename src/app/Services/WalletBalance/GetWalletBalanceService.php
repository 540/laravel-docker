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
        return $this->getCoinsBalance($wallet->coins);
    }

    private function getCoinsBalance($coins):float
    {
        $balance = 0;
        foreach ($coins as $coin)
        {
            $balance += $this->getCoinBalance($coin);
        }
        return $balance;
    }

    private function getCoinBalance($coin):float
    {
        $pastCoinPrice = $this->getCoinPrice($coin->amount, $coin->value_usd);
        $currentCoinValueUsd = $this->getCurrentCoinValueUsd($coin->coin_id);
        $actualCoinPrice = $this->getCoinPrice($coin->amount, $currentCoinValueUsd);

        return $actualCoinPrice - $pastCoinPrice;
    }

    private function getCoinPrice(float $amount, float $valueUsd):float
    {
        return $amount * $valueUsd;
    }

    private function getCurrentCoinValueUsd($coinId):float
    {
        return $this->coinDataSource->findCoinById($coinId)['price_usd'];
    }
}
