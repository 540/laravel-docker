<?php

namespace App\DataSource\API;

use App\Exceptions\WrongCoinIdException;
use Exception;
use Illuminate\Support\Facades\Http;

class CoinLoreCoinDataSource implements CoinDataSource
{
    public function findCoinById($coinId)
    {
        try{
            return Http::get( 'https://api.coinlore.net/api/ticker/?id=' . $coinId)[0];
        }catch (Exception $exception){
            throw new WrongCoinIdException();
        }
    }
}
