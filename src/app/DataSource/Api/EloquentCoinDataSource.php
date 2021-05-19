<?php

namespace App\DataSource\Api;

use Illuminate\Support\Facades\Http;

class EloquentCoinDataSource
{
    public function findCoinById($coinId)
    {
        return Http::get("https://api.coinlore.net/api/ticker/?id=" . $coinId)[0];
    }
}
