<?php

namespace App\Application\CoinDataSource;

use Mockery\Exception;

Interface BuyCoinDataSource
{
    public function findByCoinId(string $coin_id,string $wallet_id,float $amount_usd);
    //public function SellCoin(string $coin_id,string $wallet_id,float $amount_usd);
}
