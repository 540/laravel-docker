<?php


namespace Tests\Doubles;


use App\DataSource\API\CoinDataSource;
use App\Exceptions\WrongCoinIdException;

class FakeCoinLoreDataSourceManyCoins implements CoinDataSource
{
    public function findCoinById($coinId)
    {
        if($coinId === 'invalidCoinId'){
            throw new WrongCoinIdException();
        }
        if($coinId === '1'){
            return [
                'name'=>'name1',
                'symbol'=>'symbol1',
                'price_usd'=>1
            ];
        }
        return [
            'name'=>'name2',
            'symbol'=>'symbol2',
            'price_usd'=>1
        ];
    }

}
