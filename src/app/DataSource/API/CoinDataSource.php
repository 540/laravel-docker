<?php


namespace App\DataSource\API;


interface CoinDataSource
{
    public function findCoinById($coinId);
}
