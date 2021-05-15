<?php

namespace App\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinSellerDataSource;
use Exception;

class SellCoinService
{
    private EloquentCoinSellerDataSource $eloquentCoinSellerDataSource;

    public function __construct(EloquentCoinSellerDataSource $eloquentCoinSellerDataSource)
    {
        $this->eloquentCoinSellerDataSource = $eloquentCoinSellerDataSource;
    }

    public function execute(string $coinId, int $walletId, float $amountUSD)
    {
        try {
            $coin = $this->eloquentCoinSellerDataSource->findCoinById($coinId);
            $previousTotalCoinValueUSD = $coin->amount * $coin->valueUSD;
            if($previousTotalCoinValueUSD > $amountUSD) {
                $newTotalCoinValueUSD = $previousTotalCoinValueUSD - $amountUSD;
                $newCoinAmount = $newTotalCoinValueUSD / $coin->valueUSD;
                $this->eloquentCoinSellerDataSource->sellCoinOperation($coin, $walletId, $newCoinAmount);
            }
            elseif($previousTotalCoinValueUSD === $amountUSD) {
                $this->eloquentCoinSellerDataSource->deleteCoin($coin->id);
            }
            return $coin;
        }
        catch(Exception $e) {
            throw new Exception("Error");
        }
    }
}
