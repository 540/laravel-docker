<?php


namespace App\DataSource\API;


use Illuminate\Support\Facades\Http;

class EloquentCoinDataSource
{

    public function findCoinById($coin_id)
    {
        return Http::get( 'https://api.coinlore.net/api/ticker/?id=' . $coin_id)[0];
    }
}
