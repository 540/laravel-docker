<?php

namespace App\Application\CoinDataSource;

use App\Domain\Coin;
use Mockery\Exception;

Interface CoinDataSource
{
    public function findByCoinId(string $coin_id);
}
