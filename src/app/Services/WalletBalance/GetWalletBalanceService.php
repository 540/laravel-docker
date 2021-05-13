<?php

namespace App\Services\WalletBalance;

use App\DataSource\API\CoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use Exception;

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
        if($wallet == null){
            throw new Exception('a wallet with the specified ID was not found.');
        }

        $pastPrice = 0;
        $actualPrice = 0;

        foreach ($wallet->coins as $coin){
            $pastPrice += $coin->amount * $coin->value_usd;
            $actualCoin = $this->coinDataSource->findCoinById($coin->coin_id);
            if($actualCoin == null){
                throw new Exception('a coin with the specified ID was no found.');
            }
            //;
            $actualPrice += $coin->amount * $actualCoin['price_usd'];
        }

        return $actualPrice - $pastPrice;
    }
}
