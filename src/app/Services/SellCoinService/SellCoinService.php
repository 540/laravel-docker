<?php

namespace App\Services\SellCoinService;

use App\DataSource\Database\EloquentCoinDataSource;
use App\Exceptions\CoinIdNotFoundInWalletException;
use Exception;

class SellCoinService
{
    private EloquentCoinDataSource $eloquentCoinDataSource;

    public function __construct(EloquentCoinDataSource $eloquentCoinSellerDataSource)
    {
        $this->eloquentCoinDataSource = $eloquentCoinSellerDataSource;
    }

    /**
     * @throws CoinIdNotFoundInWalletException
     * @throws Exception
     */
    public function execute(string $coinId, int $walletId, float $amountUSD)
    {
        //todo findwalletid
        $coin = $this->eloquentCoinDataSource->findCoinById($coinId, $walletId);
        //todo coin lore -> Se adquiere el price_usd
        // amountADescontar = $amountUsd/coinLore['price_usd']
        $previousTotalCoinValueUSD = $coin->amount * $coin->value_usd;
        // $coin->amount > amountADescontar
        if($previousTotalCoinValueUSD > $amountUSD) {
            $newTotalCoinValueUSD = $previousTotalCoinValueUSD - $amountUSD;
            // $newTotalCoinValueUSD = $coin->value_usd - $amountUSD;
            // $newAmount = $coin->amount - $amountADescontar
            $newCoinAmount = $newTotalCoinValueUSD / $coin->value_usd; //todo coin lore value_usd
            $this->eloquentCoinDataSource->sellCoinOperation($coin, $walletId, $newCoinAmount);
        } else {
            $this->eloquentCoinDataSource->deleteCoin($coin->id);
        }//todo exception when is less than $amountUSD
    }
}
