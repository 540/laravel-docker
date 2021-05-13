<?php

namespace App\DataSource\API;

use App\Exceptions\WrongCoinIdException;

class FakeCoinDataSource implements CoinDataSource
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
