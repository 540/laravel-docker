<?php

namespace App\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinDataSource;
use Exception;

class SellCoinService
{
    private EloquentCoinDataSource $eloquentCoinDataSource;

    public function __construct(EloquentCoinDataSource $eloquentCoinSellerDataSource)
    {
        $this->eloquentCoinDataSource = $eloquentCoinSellerDataSource;
    }

    public function execute(string $coinId, int $walletId, float $amountUSD)
    {
        $coin = $this->eloquentCoinDataSource->findCoinById($coinId, $walletId);
        $previousTotalCoinValueUSD = $coin->amount * $coin->value_usd;
        if($previousTotalCoinValueUSD > $amountUSD) {
            $newTotalCoinValueUSD = $previousTotalCoinValueUSD - $amountUSD;
            $newCoinAmount = $newTotalCoinValueUSD / $coin->value_usd;
            $this->eloquentCoinDataSource->sellCoinOperation($coin, $walletId, $newCoinAmount);
        }
        elseif($previousTotalCoinValueUSD === $amountUSD) {
            $this->eloquentCoinDataSource->deleteCoin($coin->id);
        }
    }
}
