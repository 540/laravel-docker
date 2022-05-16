<?php

namespace App\Application\CoinDataSource;

use App\Domain\Coin;

Interface CoinDataSource
{
    public function findByCoinId(string $coin_id):Coin;
}
