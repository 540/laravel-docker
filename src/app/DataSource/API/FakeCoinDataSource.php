<?php


namespace App\DataSource\API;


use Exception;
use Illuminate\Support\Facades\Http;

class FakeCoinDataSource implements CoinDataSource
{

    public function findCoinById($coinId)
    {
        if($coinId === 'invalidCoinId'){
            return null;
        }
        return [
            'coin_id' => $coinId,
            'price_usd' => 50
        ];
    }
}
