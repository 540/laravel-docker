<?php

namespace App\Application\CoinDataSource;

use Mockery\Exception;

Interface BuyCoinDataSource
{
    public function findByCoinId(string $coin_id);
}
