<?php


namespace Tests\Doubles;


use App\DataSource\API\CoinDataSource;
use App\Exceptions\WrongCoinIdException;

class FakeCoinLoreDataSource implements CoinDataSource
{
    public function findCoinById($coinId)
    {
        if($coinId === 'invalidCoinId'){
            throw new WrongCoinIdException();
        }
        return [
            'name'=>'name',
            'symbol'=>'symbol',
            'price_usd'=>1
        ];
    }

}
