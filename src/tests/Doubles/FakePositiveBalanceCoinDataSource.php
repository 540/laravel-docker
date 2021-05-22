<?php

namespace Tests\Doubles;

use App\DataSource\API\CoinDataSource;
use App\Exceptions\WrongCoinIdException;

class FakePositiveBalanceCoinDataSource implements CoinDataSource
{
    public function findCoinById($coinId)
    {
        if($coinId === 'invalidCoinId'){
            throw new WrongCoinIdException();
        }
        return [
            'coin_id' => $coinId,
            'price_usd' => 50
        ];
    }
}
